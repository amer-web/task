@extends('layouts.app')
@section('content')
    <div class="container">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2> Upload and Extract/Unzip </h2>
            </div>
            <div class="panel-body">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <strong>{{ $message }}</strong>
                    </div>

                @endif
                <br><br>
                <form action="{{ url('upload-zip') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">


                        <div class="col-md-4">
                            <label>Zip</label>
                            <input type="file" placeholder="ZIP" name="zip">
                        </div>
                        @error('zip')
                        <span class="text-danger mt-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
