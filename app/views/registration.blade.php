

   <h2>Registration</h2>


	    {{ Form::open() }}
            {{ Form::text("phone", null, [ "placeholder" => "phone" ]) }} <br />
            {{ Form::password("password", [ "placeholder" => "password" ]) }} <br />
            {{ Form::password("repeat_password", [ "placeholder" => "repeat" ]) }} <br />
            {{ Form::submit("Sign up") }}
	    {{ Form::close()  }}
