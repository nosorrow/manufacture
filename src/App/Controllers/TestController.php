<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use Core\Libs\{Request, Response, Csrf, Validator};
use Core\Libs\Support\Facades\{Url, DB, Validator as ValidatorFacade};
use Symfony\Component\Finder\Finder;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Core\Libs\Support\Arr;

class TestController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function form()
    {
        view('form');
    }

    public function testStoreBlade(Request $request, Validator $validator)
    {
        $validator->for($request)
            ->make('email', 'Email address', ['required'])
            //      ->make('pass', 'Enter Password', ['required', 'min:3'])
            //      ->make('passwordconfirm', 'Confirm password', ['required', 'match:pass'])
            ->make('agree', 'Agree', ['required']);

        $validator->message(['required' => 'Please fill this field']);

        if ($validator->run() === false) {
           // return view('form');
            redirect()->back();
        }

        dump($request->input());

    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        ValidatorFacade::for($request)
            ->make('email', _t('поща'), ['required', 'email'])
            ->make('pass', '', ['required', 'min:3']);

        if (ValidatorFacade::run() === false) {
            redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function testBlade(Finder $finder)
    {
       $finder->files()->in('js')->name('*.js');

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            dump($fileNameWithExtension);
            // ...
        }
    }

    public function getProducts($lang)
    {
        $uri = route('products', ['lang' => 'bg']);
        $uri1 = route('products1');
        $uri2 = route('products2', ['lang' => 'GB']);

        $url = url($uri);
        $link = "<a href='$url'>En</a>";
        echo $link . " " . $lang;
    }

    public function ajax()
    {
        $data = DB::table('geo_city')->paginate(10);
        $data->link->setMaxPagesToShow(10);

        $data->link = sprintf($data->link);
        echo Response::json($data);
        exit;
    }

    public function search(Request $request, Csrf $csrf)
    {

        $validation = $request->validation()
            ->make('email', _t('поща'), ['required', 'email'])
            ->make('pass', _t('Парола'), ['required', 'min:4', 'max:8']);

        if ($csrf->csrf_validate() === false) {
            redirect()->to('test')->with('msg', 'Invalid CSFR tokken');
        }

        if ($validation->run() === false) {

            redirect()->back();
        }

    }

    public function sendMail()
    {
        $mail = new PHPMailer(true);
        try {

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('pdpetkov@abv.bg');     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

         //   $mail->send();
            echo 'Message has been sent';
            echo route_url('send');

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
