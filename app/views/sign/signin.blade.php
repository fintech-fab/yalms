<html>
	<body>
		@section('signin_block')
			Форма входа на портал
		@show
			<section class="signin">
				{{ Form::open([ 'url' => '/login' ]) }}

	                        {{ Form::text("phone", null, [ "placeholder" => "phone" ]) }}
	                        {{ Form::password("password", [ "placeholder" => "password" ]) }}
	                        {{ Form::submit("Sign in") }}
	                         or <a href="\registration">Sign up</a>
	            {{ Form::close()  }}
			</section>
	</body>
</html>

