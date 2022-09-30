@extends('welcome')

@section('content')
<style>
.imgLogo img{
	    width: 200px;
}
</style>
<div class="container" style="width:48%;">
<div class="login-box" style="margin: 12% auto;">
  <div class="login-logo" style="background: #000;margin-bottom:0px;">
    <a class="imgLogo" href="/">
	   <img src="{{ URL::asset('/images/logo-lg.svg') }}" alt="">
	</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in </p>

    <form action="{{route('LoginProcess')}}" method="post">
	 {{ csrf_field() }}
	 @if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.								
									@foreach ($errors->all() as $error)
										<p>{{ $error }}</p>
									@endforeach								
						</div>
						@endif
      <div class="form-group has-feedback">
          <input type="email" name="email" autocomplete="off" value="{{ old('email') }}" class="form-control" placeholder="Email" id="email">
		
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
		@if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
</div>

@endsection