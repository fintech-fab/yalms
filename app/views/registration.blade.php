

   <h2>Registration</h2>
	    {{ Form::open() }}
	        {{ Form::label('Phone', 'Phone') }}
            {{ Form::text("phone", null, [ "placeholder" => "phone" ], Input::old("phone")) }} <br />
            {{$errors->first('phone')}}<br />

			{{ Form::label('Password', 'Password') }}
            {{ Form::password("password", [ "placeholder" => "password" ]) }} <br />
            {{$errors->first('password')}}<br />

            {{ Form::label('Password_rep', 'Repeat password') }}
            {{ Form::password("repeat_password", [ "placeholder" => "repeat" ]) }} <br />
            {{$errors->first('repeat_password')}}<br />

            {{ Form::submit("Sign up") }}
             or <a href="\loginFacebook">Facebook</a>

	    {{ Form::close()  }}
