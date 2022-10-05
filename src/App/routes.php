<?php
/*
 * Class Router
 * Маршрутизация с използване на регулярни изрази по идея на "nikic"
 * https://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
 *
 * -------------------------------
 * Router::get('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::post('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::head('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::put('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::delete('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::any('search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::methods(['post', 'get'],'search/{id}', ['Admin/Folder/class@action', 'name'=>'search']);
 * Router::get('route-callback/{doc:format=pdf}/{id}', [function($doc, $id){
 *   echo 'Document name: '. $doc . ' id: ' . $id;
 *  }, 'name'=>'callback']);
 *
 * Optional Parameters:
 * -------------------------------
 * Router::any('search/{id?}', ['Admin/Folder/class@action', 'name'=>'search']);
 *
 * Regular Expression Constraints:
 * -------------------------------
 * Router::get('route/{slug}/{id:[0-9]{0,5}}', ['Admin3/controller@action', 'name'=>'route']);
 * Router::get('route/{lang:(en|bg)}/{slug}/edit/{post}/{id?:\d+}', ['test/controller@route_post']);
 * Router::get('route/{slug?:^\w+((?:\.pdf))$}', ['Admin3/controller@action', 'name'=>'route']);
 *
 * File ext: match (route/document & route/document.html)
 * ------------------------------
 * Router::get('route/{slug?:format=html}', ['Admin3/controller@action', 'name'=>'route']);
 *
 * $router = new Router();
 * $route = $router->dispatch('post/55'); // return array
 */

use Core\Bootstrap\Router;
use Core\Libs\Support\Arr;

// Create Routes colection
try {
    // Uncomment for Shut down your Site
    /*Router::site_down(function(){
        echo 'Сайта е в ремонт !';
        //setLayout('login')->render('dashboard/login');
        exit;
    });*/

    Router::get('/', [
        function ($lang = null) {
            $data = [];
            if (!Core\Libs\Support\Facades\Config::getConfigFromFile('key')) {
                $data['key'] = crypt_generate_key();
            }
            echo $lang;
            view('welcome', $data);
        },
        'name' => 'home',
    ]);

    Router::get('blade/{lang?}', [
        'TestController@testBlade',
        'name' => 'testBlade',
    ]);
    Router::any('store-blade/{lang?}', [
        'TestController@testStoreBlade'
    ]);
    Router::get('/{lang?:[a-z]{0,3}}/products', [
        'TestController@getProducts',
        'name' => 'products',
    ]);
    Router::get('/{lang?:[a-z]}/products1', [
        'TestController@getProducts',
        'name' => 'products1',
    ]);
    Router::get('{lang:[a-z]{0,3}}/products2', [
        'TestController@getProducts',
        'name' => 'products2',
    ]);

    // Redirect to Error page
    Router::any('page/{id}', ['ErrorPage@show', 'name' => 'error']);

    Router::any('search', ['TestController@search']);
    Router::any('test/{lang?}', ['TestController@form', 'name' => 'test']);
    Router::any('store', ['TestController@store']);
    Router::any('bg-cities', [
        'TestController@getCities',
        'name' => 'bgCities',
        'middleware' => 'Cors',
    ]);

    Router::get('balance/{userId}', [
        'Balancecontroller@balance',
        'name' => 'balance',
    ]);
    Router::get('spend/{userId}/{amount}', [
        'Balancecontroller@spend',
        'name' => 'spend',
    ]);
    Router::get('transfer/{fromUserId}/{toUserId}/{amount}', [
        'Balancecontroller@transfer',
        'name' => 'transfer',
    ]);
    Router::get('trans', ['Balancecontroller@trans']);
    Router::get('send-email', ['TestController@sendMail', 'name' => 'send']);
    // testing upload & resize files
    Router::get('upload', ['ImageManipulation@uploadForm']);
    Router::post('resize', ['ImageManipulation@resizeImage']);
} catch (\Exception $e) {
    die($e->getMessage());
}
