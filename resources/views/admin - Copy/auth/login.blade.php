@extends('admin.layouts.app')

@section('title', 'login here')


@section('content')

<div class="container pl-3 pr-3 bg-light">
<div class="row">
<div class="col-lg-12 text-center"></div></div>
    <div class="row mt-5 justify-content-center ">
        <div class="col-xl-3 col-lg-5 col-md-7 col-sm-8 col-11">
            <div class="panel panel-default row mb-5 pb-md-4">
            <div class="col-12 mb-md-5 mb-4 text-center">
            <img src="{{ URL::asset('images/abc-logo.svg') }}" />
            </div>
<div class="col-12 bg-white p-4 logPag">
                <div class="panel-heading size-18 pt-3 pb-2">Admin Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" id="loginForm" method="POST" action="{{ route('admin.auth.loginAdmin') }}">
                        {{ csrf_field() }}
@if (count($errors) > 0)
               <div class="alert alert-danger">
                   <strong>Whoops!</strong> There were some problems with your input.	
                   @foreach ($errors->all() as $error)
                   <p>{{ $error }}</p>
                   @endforeach	
               </div>
               @endif
               @if(session()->has('message'))
               <div class="alert alert-success">
                   {{ session()->get('message') }}
               </div>
               @endif
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address</label>

                            
                                <input id="email" autocomplete="off" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class=" control-label">Password</label>

                  
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                          
                        </div>

                        <div class="form-group">
                           
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                            
                            </div>
                        </div>
                        <div class="form-group">
                      
                                <button type="submit" class="sbmt_btn form-control"> Login</button>

                                {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a> --}}
                          
</div>

                          </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

 <script>
 $(document).ready(function(){
	 $('#loginForm').validate({ // initialize the plugin
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
        }
    });
 })
 </script>
@endsection