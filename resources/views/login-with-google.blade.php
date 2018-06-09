@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                     @if(Session('error_message'))
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        <strong>{{ Session::get('error_message') }}</strong>
                    </div>
                    @endif
                        
                    <data>
                    
                       <div><a href="{{route('login-google')}}">Login With Google</a></div> 
                       <div><a href="{{route('login-facebook')}}">Login With facebook</a></div> 
                       <div><a href="{{route('login-linkedin')}}">Login With linkedIn</a></div> 
                       
                    </data>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


