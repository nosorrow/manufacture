---
title: Pagination
layout: layout.hbs
---
Pagination
-----

##### Simple Pagination

```php
<?php

class Pagination extends Controller
{
    public function form(Pagination $pagination)
    {
        $data['cities'] = (DB::table('city')->paginate(10));

        view('pagination', $data);
    }
}
```
View paginated data

```php

<div class="container">
    <h1>DataBase Testing</h1>
    <div class="row">
        <div class="col-md-10">
            <?php foreach ($cities->data as $city):?>
            <p><?= $city->city_id . ' '.$city->name  ." : " . $city->lat . ','. $city->lng?></p>
            <?php endforeach ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php $cities->link->setUlClass("pagination pagination-sm");?>
                    <?= $cities->link ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

```

Може да използваме темплейта с линкове на JasonGrimes Paginator:

```php

<div class="container">
    <h1>DataBase Testing</h1>
    <div class="row">
        <div class="col-md-10">
            <?php foreach ($cities->data as $city):?>
            <p><?= $city->city_id . ' '.$city->name  ." : " . $city->lat . ','. $city->lng?></p>
            <?php endforeach ?>
        </div>
    </div>
</div>
<?php $paginator = $cities->link;?>
<?php include_once partial('paginationSmall'); ?>

```

##### Manual Pagination
Методите на Pagination класа:

```php
$pagination->total($count);
$pagination->url_pattern(site_url('...') . '?page=(:num)');
$pagination->total($count);
$pagination->per_page($itemsPerPage);
$pagination->setMaxPagesToShow($maxPagesToShow);
$pagination->setPreviousText($previousText);
$pagination->setNextText($nextText);
$pagination->getLimit(); // връща sql query (' LIKE 10 OFFSET 20);
$pagination->paginate($n);
```
Методите на ManufacturePaginator extends Paginator:

```php
setUlClass($ulClass);
setLiClass($liClass);
setLinkClass($linkClass);
```

За класът JasonGrimes Paginator виж документацията [docs](https://github.com/jasongrimes/php-paginator)

```php
<?php

class Pagination extends Controller
{
    public function paginateDatafromDB(Pagination $pagination)
    {
        $count = DB::table('geo_city')->count();

        $pagination->total($count);
        $pagination->url_pattern(site_url('someUri') . '?page=(:num)');
        
        $link = $pagination->paginate(10);
        $link->setUlClass("pagination pagination-sm");

        $data['link'] = $link;
        $data['paginator'] = $link;
        $data['cities'] = DB::table('geo_city')->rawLimit($pagination->limit)->get(\PDO::FETCH_ASSOC);

        view('pagination', $data);
    }
}
```

View paginated data

```php

<div class="container">
    <h1>DataBase Testing</h1>
    <div class="row">
        <div class="col-md-10">
            <?php foreach ($cities as $city):?>
                <p><?= $city['name'] ?></p>
            <?php endforeach ?>
            <nav aria-label="Page navigation example">
                <?= $link ?>
            </nav>
        </div>
    </div>
</div>


```
##### Ajax pagination

В рутера :
```php
<?php
   Router::any('ajax', ['TestController@ajax', 'name'=>'ajax']);

```

```php
<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use Core\Libs\Support\Facades\DB;
use Core\Libs\Pagination;
use Core\Libs\Response;

class TestController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
   
    public function paginateData(Pagination $pagination)
    {      
        view('pagination-ajax');
    }

    public function ajax()
    {
        $pag = DB::table('geo_city')->paginate(10);
        $data['link'] = sprintf($pag->link);
        $data['data'] = $pag->data;
     
        echo Response::json($data);
        return;
        
        //==============================================
        // Or use this code
        $data = DB::table('geo_city')->paginate(10);
        $data->link->setMaxPagesToShow(15);
        $data->link = sprintf($data->link);
        echo Response::json($data);
        return;
        //===============================================
    }
}

```

View : pagination-ajax.php

```html
<div class="container">
    <h1>DataBase AjaxPagination</h1>
    <div class="row">
        <div class="col-md-10">
            <!-- тук js ще покаже резултатът -->
            <div style="height: 300px">
                <p class="result"></p>
            </div>
            <nav id="paginator" style="position: fixed; bottom: 0"></nav>
        </div>
    </div>
</div>
<script>
    function displayPaginated(link) {
        $.get(link, function (data) {
            // показваме link с номерата на страниците
            $('#paginator').html(data.link);
            $.each(data.data, function (key, val) {
                // показваме първите резултати от страницирането
                $(".result").append('<p>' + val.name + '</p>');
            });
        })
    }

    displayPaginated('ajax');

    // При клик на page links
    $(document).on("click", '.page-item a', function (e) {

        e.preventDefault();

        var link, href;

        link = $(this);
        href = link.attr('href');

        $('#paginator ul li').removeClass('active');
        link.parent().addClass('active');
        $('.result').empty();

        displayPaginated(href);
    })

</script>

```
