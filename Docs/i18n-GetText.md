---
title: Localization with - GetText library
layout: layout.hbs
---

### Функции за превод с GetText
Функциите за локализация на Манифактурата предоставят удобен начин за извличане на низове на 
различни езици, което позволява лесно да се поддържат множество езици във приложението.
Езиковите файлове се намират в директория <code>App/Locale/ll_CC/LC_MESSAGES</code> 
Имената на езиковите директории съответстват на езиковия local във формат <code>ll_CC</code>,  
където "ll" e двубуквен езиков код по ISO 639 и „CC“ е двубуквена държава по ISO 3166. Може да
използвате като име на директория и само двубуквен езиков код по ISO 639 примерно "bg"
Пример: <strong>bg_BG , en_US, fr_FR, bg, en</strong> 
Добър вариант е да се изполва софтуер за създаване на файловете като Poedit. 

```php
/locale
    /en_US
        /LC_MESSAGES
            en_US.po
            en_US.mo
            theme.mo
            theme.po
    /fr_FR
        /LC_MESSAGES
            en_US.po
            en_US.mo
            theme.mo
            theme.po
    /de
            /LC_MESSAGES
                de.po
                de.mo
                theme.mo
                theme.po
```

##### Извличане на низове за превод
Функции:
```php
# $domain - не задължителен аргумент

# Връщат преведения стринг
tr_($text, $domain);
tn_($singular, $plural, $number, $domain);
# псевдоними
_t($text, $domain) 
_tn($singular, $plural, $number, $domain)

# Отпечатват преведения стринг
__t($text, $domain);
__tn($singular, $plural, $number, $domain);

```

###### Пример:

Ако в нашият en_US.po и theme.po файл имаме

```text
msgid "Здравей Свят"
msgstr "Hellow World"

```

```php
<?php
// Превода ще е от en_US.mo файл
echo _t('Здравей Свят');
__t('Здравей Свят');
// Превода ще е от theme.mo файл
echo _t('Здравей Свят', 'theme');
__t('Здравей Свят', 'theme');
```

#####  Множествено число

Кода в <code>theme.po файл</code>

```text
msgid "стая"
msgid_plural "стаи"
msgstr[0] "Room"
msgstr[1] "Rooms"
```

```php
<?php

echo _tn("стая", "стаи", 1, 'theme');
echo _tn("стая", "стаи", 2, 'theme');
// output Room | Rooms
```

Не забравяйте да компилирате файлът oт .po  в .mo 

Командата от терминала е :

```text
msgfmt en_US.po -o en_US.mo
```

##### Добавяне на език

Езикът по подразбиране е в <code>config.php</code>

```php
'lang' => 'bg_BG'
```

Можем да сменяме езиците в нашето приложение по няколко начина - като поставим в url($_GET) , session или cookie
<code>'lang'</code> с името на локала.

<b>Пример в URL:</b>
В <code>router.php</code>

```php
Router::get('posts/{lang?}', ['PostController@posts']);
// Регулярният израз допуска само en и bg като стойности на lang в url
Router::get('posts/{lang:^(en|bg)$}', ['PostController@posts']);
```
Url -> <code>https://my-site.com/posts/en</code>

<b>Пример с cookie:</b>

```php

    public function someActionInClassController(Request $request)
    {
       /* .................................... */
        $request->set_cookie('lang' , 'en_GB');
        dump(get_locale_lang());
    }

```

```php
<?php 
dump(get_locale_lang());
// output en_US
```

###### get_locale_lang()
Връща текущия език от локала
```php
<?php
$locale = get_locale_lang();
// output en_US;
```
