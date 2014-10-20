@section('title')
   Create new course
@stop

@include('includes.header')

    {{ Form::open(array('url' => 'course')) }}
        {{ Form::label('name', 'Course name') }}
        {{ Form::text('name') }}

        @if($errors->has('name'))
           @foreach ($errors->all() as $error)
              <div class="error">*{{ $error }}</div>
          @endforeach
        @endif

        {{ Form::submit('Add course!') }}
    {{ Form::close() }}

@include('includes.footer')