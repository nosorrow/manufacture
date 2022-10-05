@extends('Errors.head')

@section('content')
    <style>
        h1 {
            font: 700 8em 'Helvetica';

        }
        .error-template {
            padding: 40px 15px;
            text-align: center;
        }

        .error-actions {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .error-actions .btn {
            margin-right: 10px;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>!</h1>
                    <h2>Unexpected error</h2>
                    <div class="error-details">
                        Sorry, but you can go back!
                    </div>
                    <div class="error-actions">
                        <a href="<?php echo site_url();?>" class="btn btn-dark btn-lg">
                            Back Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
