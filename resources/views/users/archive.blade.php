@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Trashed Users') }}</div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Photo</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($archive as $key => $user)
                                        <tr>
                                            <th scope="row" class="py-3">{{ $key + 1 }}</th>
                                            <td><img src="{{ $user->avatar }}"
                                                    style="width: 40px; height:40px; border-radius:10px;" /></td>
                                            <td class="py-3">{{ $user->full_name }}</td>
                                            <td class="py-3">{{ $user->email }}</td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-success m-1" form="restore-user-{{ $user->id }}">Restore</button>
                                                <button type="submit" class="btn btn-sm btn-danger m-1" form="delete-user-{{ $user->id }}">Delete</button>
                                                <button type="button" class="btn btn-sm btn-secondary m-1">View</button>
                                            </td>

                                            <form action="{{ route('users.restore', $user->id) }}" method="post" id="restore-user-{{ $user->id }}">
                                                @method('PATCH')
                                                @csrf
                                            </form>

                                            <form action="{{ route('users.delete', $user->id) }}" method="post" id="delete-user-{{ $user->id }}">
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
                            <div>
                                <a href="{{route('users.index')}}" class="btn btn-sm btn-primary">Users</a>
                            </div>
                            <div class="mt-3">
                                {{$archive->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
