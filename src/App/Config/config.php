<?php

return [
    /* ===============================
     * Composer autoload
     * =================*/
    'composer_autoload' => true,

    /* ================================
     *  whoops ( Pretty error display )
     *  development
     *  production
     * =================================*/
    'environment' => 'development',

    /* ===============================================================
     * true -> записва в лог файл когато 'environment' => 'production'
     * ===============================================================*/
    'logger'=>true,

    /* ===============================================================
     * При critical log изпраща и email когато 'environment' => 'production'
     * ===============================================================*/
    'critical-email'=>'',

    /* ===========================================================================
     * base_url е пълният  URL адрес до index.php ( http://myhost.com/folder )
     *
     * Ако е празен стринг ще вземе хоста от глобалните променливи.
     * ===========================================================================*/
    'base_url' => '',

    /* =========================================
     * Ако не наличен $_SERVER'REQUEST_SCHEME'
     * http
     * https
     *
     *==========================================*/
     'REQUEST_SCHEME' => 'http',

    /* =========================================
     *  View:
     *  'php' за рендериране с чист php код
     *  'blade" laravel.com -> blade template
     * =========================================*/
    'template_engine'=>'',

    /* ========================================
     *Timezone
     * http://php.net/manual/en/timezones.php
     *
     * ========================================*/
    'timezone' => 'Europe/Sofia',

    /* ============================================
     * Language
     * A locale name usually has the form ‘ll_CC’.
     * Here ‘ll’ is an ISO 639 two-letter language code,
     * and ‘CC’ is an ISO 3166 two-letter country
     * bg_BG , en_US
     * or has the form "ll" - ISO 639 two-letter language code
     * bg, en, fr, de
     * ============================================*/
    'lang' => 'bg',
//    'ISO3166' => false,
    //'domain' => 'theme',

    /* =============================================
     * Директория по подразбиране
     * за качване на файлове
     *
     * =============================================*/
    'upload_directory' => 'uploads/',

    /* ============================================
     * Session
     * Всички сесии са http-only
     *
     * Ako session_handler => database
     * в папка Libs/Session/session-database.sql
     * се намира Mysql дъмп на таблицата за сесиите
     *
     * ============================================*/
    'session_handler' => 'file', // 'file' or 'database'

    'session_name' => '_manufac_sess',

    'session_cookie_lifetime' => 7200,

    'session_save_path' => APPLICATION_DIR . 'storage' . DIRECTORY_SEPARATOR . 'tmp',

    'session_secure' => false,

    'session_regenerate' => true,

    /* ======================================================
     *  Encrypt
     *  Generate new key!
     * ======================================================*/
    'key' => 'mL26rIdJo0+wDaJEaBe1a9kwySyRMs2Yo8kBLvA6Mvc=', 

    'cipher' => 'AES-256-CBC',

];
