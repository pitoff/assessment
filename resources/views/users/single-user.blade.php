@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('User') }}</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{$user->avatar}}" style="width:200px; height:200px;"/>
                            </div>

                            <div class="col-md-6">
                                <p class="mt-3">
                                    <strong class="text-bold m-3">NAME:</strong>
                                    <strong>{{$user->prefixname}}. {{$user->full_name}}</strong>
                                </p>
                                <p class="mt-3">
                                    <strong class="text-bold m-3">SUFFIX:</strong>
                                    <strong>{{$user->suffixname}}</strong>
                                </p>
                                <p class="mt-3">
                                    <strong class="text-bold m-3">EMAIL:</strong>
                                    <strong>{{$user->email}}</strong>
                                </p>
                                <p class="mt-3">
                                    <strong class="text-bold m-3">USERNAME:</strong>
                                    <strong>{{$user->username}}</strong>
                                </p>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="{{route('users.index')}}" class="btn btn-sm btn-primary">Users</a>
                            <a href="{{route('user.details', $user->id)}}" class="btn btn-sm btn-primary">View Details History</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
