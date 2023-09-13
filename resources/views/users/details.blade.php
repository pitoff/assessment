@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('User Background Information') }}</div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Avatar</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Middle Initial</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Gender</th>
                                        {{-- <th scope="col">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($details as $key => $user)
                                        <tr>
                                            <th scope="row" class="py-3">{{ $key + 1 }}</th>
                                            <td><img src="{{ $user->user->avatar }}"
                                                    style="width: 40px; height:40px; border-radius:10px;" /></td>
                                            <td class="py-3">{{ $user->user->full_name }}</td>
                                            <td class="py-3">{{ $user->value }}</td>
                                            <td class="py-3">{{ $user->user->email }}</td>
                                            <td class="py-3">{{ $user->status }}</td>
                                            
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                                <div>
                                    <a href="{{ route('users.show', $user->user_id) }}" class="btn btn-sm btn-primary">View User</a>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
