@extends('blade.layout.head')
@section('content')
    <div class="container">
        <h3 class="text-center">File Upload</h3>
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form method="post" action="{{site_url('resize')}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="avatar">Select file</label>
                        <input type="file" name="avatar" multiple
                               class="form-control {{ ($errors->has('file')) ? 'is-invalid' : 'valid' }}"
                               id="file">
                        <small id="fileHelp" class="invalid-feedback">
                            {{($errors->first('file'))}}
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
