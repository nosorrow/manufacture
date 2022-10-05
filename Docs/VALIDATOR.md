---
title: Валидация на данни
layout: layout.hbs
---

### Валидация на request с манифактурата

Валидацията се извършва от класа:

Core\Libs\Validator

Логиката се изгражда на принципа:


```php

// $validator is instance of Core\Libs\Validator

$validator->for($dataForValidation)
    ->make('fieldName', 'Label', ['validation_rule'], [$Optional_customErrorMessage]);
    
if($validator->run() === false {
    // .... redirect to errors 
}     
 
```

Ако данните от полето са в масив примерно:


```html
    <input type="text" name="emails[]">
```

то в изграждането на валидацията може да използваме маска :


```php

$validator->for($request)->make('emails.*', .....);

```

##### Пример на валидация в контролера


```php
<?php 

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Validator;
use Core\Libs\Request;

class MyController extends Controller
{
    public function testStoreBlade(Request $request, Validator $validator)
    {
        $validator->for($request)
            ->make('email', 'Email address', ['required', 'email'])
            ->make('pass', 'Enter Password', ['required', 'min:8'])
            ->make('passwordconfirm', 'Confirm password', ['required', 'match:pass'])
            // ":file" ще покаже името на файлът в съобщението за грешка.
            ->make('file', ':file', ['mimes:jpeg,gif,bmp'])
            ->make('agree', 'Agree', ['required']);
        
        if($validator->run() === false){

            redirect()->back();
        }

        view('blade.homepage');
    }
}

```

##### Валидиране на други данни:

```php
<?php 

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Validator;

class MyController extends Controller
{
    public function testStoreBlade(Validator $validator)
    {
        $data = ['text'=>'Some text here'];
        $validator->for($data)
            ->make('text', '', ['max:255']);
                 
        if($validator->run() === false){

            redirect()->back();
        }
    }
}

```

##### Зареждане на грешките от file uploader във Валидатора

```php

<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Validator;
use Core\Libs\Request;

class MyController extends Controller
{
    public function store(Request $request)
    {
       $upload = $request->file()->upload('file');
       
               if ($upload->hasError() && $upload->getErrorCode() !=4 ) {
                   $request->validation()->errors->file = $upload->getError(true);
               }
       
               if ($request->validation()->run() == false) {
       
                   redirect()->back();
               }
    }
}

```


##### Валидиране с Facades:

Статично извикване на класа Validator


```php
<?php 

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Utils\Facades\Validator;
use Core\Libs\Request;

class MyController extends Controller
{
    public function store(Request $request)
    {
        $data = ['text'=>'Some text here'];
        Validator::for($data)
                ->make('text', '', ['max:255']);
                 
        if(Validator::run() === false){

            redirect()->back();
        }
    }
}

```

##### Валидация на полета от форма с класа Request


```php

<?php 

class MyController extends Controller
{
    public function store(Request $request)
    {
        $validation = $request->validation()
                    ->make('email.*.female', 'email address', ['required', 'email', 'max:25']);
                
        if($validation->run() === false){

            redirect()->back();
        }
    }
}

```

##### Валидиращите правила могат да бъдат поставени в масив


```php

<?php
$fieldRules = [
    'email'=>[
        'label'=>'Email address',
        'rules'=>['required', 'email'],
        'message'=>['required'=>'Cannot be empty', 'email'=>'Not valid email'] 
    ]
];

 $validator->make($fieldRules);
```
### Собствени правила и слагане на грешки

С помоща на анонимна функция може да се поставят собствени правила за валидиране

```php
<?php
    $validator->for($data)->make('product_name', 'продукт', ['required',
        function($label, $value){
            if ($value == 'bluza'){
                // съобщение за грешка
                return (string)($label . " e bluza");
            }
    }]);

```

### Собствени съобщения за грешки

Подайте масив в метода message($array) :

1 Собствени съобщения за определено поле.

```php
<?php
$messages = [
    'user' =>['required'=>'Въведете потребителско име'],
    'email'=>[
              'required'=>'Не сте попълнил това поле',
              'email'=>'Въведете валиден емейл адрес'
           ],
    'addresses.*' =>["max" =>"Полето {label} не може да бъде по-голямо от {arg} символа"]
   ];

$validator->message($messages);

```
2 Собствени съобщения за всички полета.

```php
<?php
$messages = [
    'required'=>'Не сте попълнил това поле',
    'mail'=>'Въведете валиден емейл адрес'
];

$validator->message($messages);

```

Грешките от валидацията се записват в обекта MessageBag 
и се извикват във View от флаш променливата $errors 

```php

<?php
    if ($errors->any()) {
        foreach ($errors->all() as $error){
            echo $error;
        }
    }
;?>


```

###### Всички методи на обекта MessageBag:


* set()
* get()
* all()
* first(name)
* count()
* isEmpty()
* isNotEmpty()
* any()
* has()
* toJson()


##### пример за html форма:


```php

<form method="post" action="<?php echo site_url('store') ?>">
    <div class="form-group">
        <label for="email">Email address</label>
        <input type="text" name="email"
               class="form-control <?php echo ($errors->has('email')) ? 'is-invalid' : $valid ?>"
               id="email" placeholder="Enter email" value="<?php echo old('email') ?>">
        <small id="emailHelp" class="invalid-feedback">
            <?php echo($errors->first('email')) ?>
        </small>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input name="password" type="password"
               class="form-control <?php echo ($errors->has('password')) ? 'is-invalid' : $valid ?>"
               id="password" placeholder="Password">
        <small id="emailHelp" class="invalid-feedback">
            <?php echo($errors->first('password')) ?>
        </small>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" name="agree"
               class="form-check-input <?php echo ($errors->has('agree')) ? 'is-invalid' : $valid ?>"
               id="exampleCheck1" <?php echo (old('agree')) ? 'checked' : '' ?>>
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
        <small class="invalid-feedback">
            <?php echo($errors->first('agree')) ?>
        </small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

```

##### II начин за валидация не се пропоръчва и ще излезе от употреба



```php
<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Validator;
use Core\Libs\Request;

class MYFormValidation extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    
    /**
    *  показва станицата с формата
    */
    public function DisplayForm()
    {
        return view('formView');
    }

    /**
    *  Валидира данните
    */
    public function StoreForm(Request $request, Validator $validator)
    {
        // логика за валидация - създава правила 
        $validator->make('product_name', 'продукт', ['required', 'name'])
                   ->make('product_size', 'количество', ['required', 'integer', 'max:10']);
        
        // стартира проверката
        if ($validator->run() === false) {
             // форматира грешките
            $data['errors'] = $validator->errors('', '<li>', '</li>', 
                                        '<div class="alert alert-danger" role="alert">%s</div>');
             //зарежда view с грешките в прменлива {$errors}
             view('Dashboard/Products/products_menu_new', $data);
        
        } else { 
           //your code here - insert in DB 
        }
    }

}
```
### Как да покажем грешките във View 

##### Показване на всички грешки в списък

```php
<div class="col-md-6">
    <?php 
        if (isset($errors)) {
            echo $errors;
        }
    ?>
</div>
```
###### По подразбиране грешките са форматирани с темплейт пр.

```php
<div class="alert alert-danger" role="alert">
    <p>Грешка-1</p>
    <p>Грешка-2</p>
    <p>Грешка-3</p>
</div>
```

###### Форматиране от разработчика
    
```php
$data['errors'] = $validator->errors('', '<li>', '</li>', '<div class="ownAlert">%s</div>');
```

##### Показване на грешките за всяко поле помоща на validation helpers:

```php
<div class="form-group<?php echo has_error('product_name') ? ' has-error':'';?>">
    <label for="product_size">количество</label>
    <input name="product_size" type="text" class="form-control" id="product_size"
           value="<?php echo oldValue('product_size') ? oldValue('product_size') : $product['product_size']; ?>"
           placeholder="количество">
    <?php echo validation_error('product_size'); ?>
</div>
```

### Helpers

validation helper | върната стойност 
--- | ---
validation_error('field-name'); | връща първото съобщение за грешка
old('field-name') | като oldValue с тази разлика че взима грешките от флаш сесия
oldValue('field-name'); | връща вече попълнени данни , на валидираното поле 
has_error('field-name') | връща true ако има грешки при валидацията

## Правила за валидация

Правило | Съобщение за грашка 
--- | --- 
after:date | Полето {label} не e дата след {arg}
alpha | Полето {label} може да съдържа само латински букви.
alpha_num or alnum | Полето {label} може да съдържа само латински букви и цифри.
alpha_dash | Полето {label} може да съдържа само латински букви, цифри, долна черта и тире.
before:date | Полето {label} не e дата преди {arg}
date:format | Полето {label} не e валидна дата
date_format | Полето {label} не е валиден формат на дата - {arg}
different:value | Полето {label} не трябва да съвпада с полето {arg}.
email | Полето {label} не e валиден e-mail
exact:value | Полето {label} трябва да бъде дълго точно {arg} символа.
greater:value | Полето {label} трябва да съдържа число, по-голямо от {arg}
gt:field | Полето {label} трябва да съдържа число, по-голямо на това в полето {arg}
greater_equal:value | Полето {label} трябва да съдържа число, по-голямо или равно на {arg}
gte:field | Полето {label} трябва да съдържа число, по-голямо или равно на това в полето{arg}
file | "Полето {label} трябва да е файл";
filesize:2 | "Файлът {label} трябва да е по-малък от {arg}MB";
integer | Полето {label} може да съдържа само цели числа.
is_numeric | Полето {label} трябва да е число.
in:val1, val2, val3 | Полето {label} трябва да е измежду стойностите {arg}.
less:value | Полето {label} трябва да съдържа число, по-малко от {arg}
lt:field | Полето {label} трябва да съдържа число, по-малко от това в полето {arg}
less_equal | Полето {label} трябва да съдържа число, по-малко или равно от {arg}
lte:field | "Полето {label} трябва да съдържа число, по-малко или равно от това в полето {arg}";
max:value | Полето {label} e по-голямо от {arg} символа
match:field | Полето {label} не съвпада с полето {arg}
min:value | Полето {label} e по-малко от {arg} символа
mimes:jpg,gif | "{label}" трябва да е файл от типа: {arg}
name | Полето {label} може да съдържа само букви и интервал, включително на кирилица.
regex:(regex) | Полето {label} не е в правилен формат.
regex_not:(regex) | Полето {label} не е в правилен формат.
required | Полето {label} e задължително
unique:table.col | Полето {label} трябва да съдържа уникална стойност.
unique_except:table.col.exc-col.exc-col-id | Полето {label} трябва да съдържа уникална стойност.
url | Полето {label} не e валиден URL

##### unique_except


Понякога може да искате да пренебрегнете даден идентификационен номер по
време на уникалната проверка. Ако имаме форма за актуализация на профил примерно
и използваме валидационното правило unique за email , то ще бъде хвърлена грешка, тъй като
email съществува  в базата, а ние искаме да проверим дали нововъведеният  
email съществува за друг потребител.Правилото unique_except проверява в базата за уникален email , 
който не е на потребител с id = 666 unique_except:users.email.id.666


```php

unique_except:users.email.id.666

```
