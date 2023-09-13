@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Users') }}</div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $key => $user)
                                        <tr>
                                            <th scope="row" class="py-3">{{ $key + 1 }}</th>
                                            <td><img src="{{ $user->avatar }}"
                                                    style="width: 40px; height:40px; border-radius:10px;" /></td>
                                            <td class="py-3">{{ $user->full_name }}</td>
                                            <td class="py-3">{{ $user->email }}</td>
                                            <td class="py-3">{{ $user->type }}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a href="{{ route('users.edit', $user->id) }}" type="button" class="btn btn-sm btn-success">Edit</a>
                                                        
                                                        @if ($user->type !== 'Technical')
                                                            <button type="submit" class="btn btn-sm btn-danger m-1" form="delete-user-{{ $user->id }}">Archive</button>
                                                        @endif

                                                        <a href="{{ route('users.show', $user->id) }}" type="button" class="btn btn-sm btn-secondary">View</a>
                                                    </div>    
                                                </div>
                                            </td>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="post"
                                                id="delete-user-{{ $user->id }}">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">No data available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                            @if ($trashed)
                                <div>
                                    <a href="{{ route('users.trashed') }}" class="btn btn-sm btn-primary">View Archive</a>
                                </div>
                            @endif
                            <div class="mt-3">
                                {{$users->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
