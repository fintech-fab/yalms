

   <h2>Registration</h2>
   {{
   	        Debugbar::info($errors);
   	        Debugbar::info(Input::old("phone"));
   	        Debugbar::info(Input::old("password"));


   }}
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
	    {{ Form::close()  }}
