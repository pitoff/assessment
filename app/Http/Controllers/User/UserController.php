<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $prefix;
    public $userService;

    public function __construct(UserServiceInterface $userServiceInterface, UserService $userService)
    {
        $this->prefix = $userServiceInterface->prefixName();
        $this->userService = $userService;
    }

    public function index()
    {
        if($this->userService->list());
            return view('users.index', $this->userService->list());
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['prefix'] = $this->prefix;
        return view('users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if($this->userService->store($request));
            return redirect(route('users.index'))->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if($this->userService->find($id));
            return view('users.single-user', $this->userService->find($id));
    }

    public function userDetails($id)
    {
        if($this->userService->findDetails($id));
        return view('users.details', $this->userService->findDetails($id));
    }

    public function trashed()
    {
        return view('users.archive', $this->userService->listTrashed());
    }

    public function restore($id)
    {
        if($this->userService->restore($id));
            return back()->with('success', 'User record restored');
    }

    public function forceDelete($id)
    {
        if($this->userService->destroy($id));
            return back()->with('success', 'User permanently removed');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['prefix'] = $this->prefix;
        $data['user'] = User::where('id', $id)->findOrFail($id);
        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if($this->userService->update($request, $id));
            return redirect(route('users.index'))->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($this->userService->delete($user->id));
            return back()->with('success', 'User was deleted successfully');
    }
}
