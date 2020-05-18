<?php
/*
include_once 'lib/gettext.inc';

define('PROJECT_DIR', realpath('./'));
define('LOCALE_DIR', PROJECT_DIR .DIRECTORY_SEPARATOR . 'locale');
define('DEFAULT_LOCALE', 'en_US');

$supported_locales = array('en_US', 'sr_CS', 'de_CH');
$encoding = 'UTF-8';

$locale = (isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;

// gettext setup
T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
$domain = 'message';
T_bindtextdomain($domain, LOCALE_DIR);
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);
*/

/*include_once SYSTEM_DIR . "Libs/i18n/php-gettext/gettext.php";

include_once SYSTEM_DIR . "Libs/i18n/php-gettext/streams.php";*/
use Core\Libs\Support\Facades\Config;

function get_locale_lang()
{
    if (!empty(request_get('lang'))) {

        $locale_lang = request_get('lang');

    } elseif (!empty(sessionData("lang"))) {

        $locale_lang = sessionData("lang");

    } elseif (!empty(get_cookie('lang'))) {

        $locale_lang = get_cookie('lang');

    } elseif (Config::getConfigFromFile('lang')) {

        $locale_lang = Config::getConfigFromFile('lang');

    } else {
        $locale_lang = "bg_BG";
    }

    if(strpos($locale_lang, '_') === false){
        $locale_lang = strtolower($locale_lang);

    }

    return $locale_lang;
}

/**
 * @param string $domain_name
 * @return gettext_reader
 */
function init_i18n($domain_name = '')
{
    $locale_lang = get_locale_lang();

    if(!$domain_name){
        $domain = LOCALE_DIR . "$locale_lang/LC_MESSAGES/$locale_lang.mo";

    } else {
        $domain = LOCALE_DIR . "$locale_lang/LC_MESSAGES/$domain_name.mo";
    }

    $locale_file = new FileReader($domain);

    $locale_fetch = new gettext_reader($locale_file);

    return $locale_fetch;
}

/**
 * @param $text
 * @param string $domain
 * @return string
 */
function tr_($text, $domain = '')
{
    $locale_fetch = init_i18n($domain);

    return $locale_fetch->translate($text);
}

/**
 * @param $singular
 * @param $plural
 * @param $number
 * @param string $domain
 * @return string
 */
function tn_($singular, $plural, $number, $domain = '')
{
    $locale_fetch = init_i18n($domain);

    return $locale_fetch->ngettext($singular, $plural, $number);
}

/**
 * @param $text
 * @param string $domain
 */
function __t($text, $domain = ''){

    echo tr_($text, $domain);
}

/**
 * @param $singular
 * @param $plural
 * @param $number
 * @param string $domain
 */
function __tn($singular, $plural, $number, $domain = ''){
    echo tn_($singular, $plural, $number, $domain);

}

/**
 * @param $text
 * @param string $domain
 * @return string
 */
function _t($text, $domain = ''){
    return tr_($text, $domain);
}

/**
 * @param $singular
 * @param $plural
 * @param $number
 * @param string $domain
 * @return string
 */
function _tn($singular, $plural, $number, $domain = ''){
    return tn_($singular, $plural, $number, $domain);
}
