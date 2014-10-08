<!doctype html>
<html lang="en">

<head>
{{ HTML::style('css/main.css'); }}
	<meta charset="UTF-8">
	<title>YaLMS</title>
</head>
<body>
	<nav>{{ link_to_route('course.index', 'Courses') }}</nav>
	<section class="authorization-block">
	    {{ Form::open([ 'url' => '/login' ]) }}
            {{ Form::text("phone", null, [ "placeholder" => "phone" ]) }}
            {{ Form::password("password", [ "placeholder" => "password" ]) }}
            {{ Form::submit("Sign in") }}
             or <a href="\registration">Sign up</a>
	    {{ Form::close()  }}
	</section>
	<div class="welcome">
        <img src="/images/logo.png">
		<h1>Yet another LMS</h1>
	</div>
</body>
</html>