@extends('blade.layout.head')
@section('content')
    <div class="container">
        <h3 class="text-center">Register form</h3>
        <p>{{__t("Парола")}}</p>
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <h2>Hello</h2>
                <h3>
                    @if ($s = flash('success'))
                        {{$s}}
                    @endif
                </h3>
                <form method="post" action="{{site_url("store-blade/").request_get('lang')}}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="text" name="email"
                               class="form-control {{ ($errors->has('email')) ? 'is-invalid' : $valid }}"
                               id="email" placeholder="Enter email" value="{{old('email')}}">
                        <small id="emailHelp" class="invalid-feedback">
                            {{($errors->first('email'))}}
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="pass" type="pass"
                               class="form-control {{($errors->has('pass')) ? 'is-invalid' : $valid}}"
                               id="password" placeholder="Password" value="{{old('pass')}}">
                        <small id="passwordError" class="invalid-feedback">
                            {{($errors->first('pass'))}}
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirm Password</label>
                        <input name="passwordconfirm" type="password"
                               class="form-control {{($errors->has('passwordconfirm')) ? 'is-invalid' : $valid}}"
                               id="password2" placeholder="Confirm Password">
                        <small id="passwordconfirmError" class="invalid-feedback">
                            {{($errors->first('passwordconfirm'))}}
                        </small>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="agree"
                               class="form-check-input {{($errors->has('agree')) ? 'is-invalid' : $valid}}"
                               id="exampleCheck1" {{(old('agree')) ? 'checked' : ''}}>
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        <small class="invalid-feedback">
                            {{$errors->first('agree')}}
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
