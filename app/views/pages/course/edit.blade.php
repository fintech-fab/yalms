@section('title')
   Edit course {{$courseName}}
@stop

@include('includes.header')
    {{ Form::open(array('url' => $url, 'method' => 'PUT')) }}
        {{ Form::label('name', 'Course name') }}
        {{ Form::text('name',$courseName) }}

        @if($errors->has('name'))
            @foreach ($errors->all() as $error)
                <div class="error">*{{ $error }}</div>
            @endforeach
        @endif

        {{ Form::submit('Save change!') }}
    {{ Form::close() }}

@include('includes.footer')