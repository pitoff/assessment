<?php

namespace App\Services;

use App\Events\UserSaved;
use App\Interfaces\UserServiceInterface;
use App\Models\Details;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class UserService implements UserServiceInterface
{
    protected $model;
    protected $request;

    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    public function rules($id = null)
    {
        if($id){
            return [
                'firstname' => 'required',
                'prefixname' => 'nullable',
                'middlename' => 'nullable',
                'lastname' => 'required',
                'suffixname' => 'nullable',
                'username' => 'required',
                'email' => 'required|email',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'type' => 'nullable',
            ];
        }else{
            return [
                'firstname' => 'required',
                'prefixname' => 'nullable',
                'middlename' => 'nullable',
                'lastname' => 'required',
                'suffixname' => 'nullable',
                'username' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'type' => 'nullable',
            ];
        }
    }

    public function list()
    {
        $data['users'] = $this->model->paginate(2);
        $data['trashed'] = $this->model->onlyTrashed()->first();
        return $data;
    }

    public function store($request)
    {
        $data = $request->validated();
        $data['photo'] = $this->upload($request->photo);
        $createUser = $this->model->create($data);
        $this->saveUserDetails($createUser);
        return $createUser;
    }

    public function saveUserDetails($user)
    {
        UserSaved::dispatch($user);
    }

    public function find(int $id)
    {
        $data['user'] = $this->model->where('id', $id)->findOrFail($id);
        return $data;
    }

    public function findDetails($id)
    {
        $data['details'] = Details::where('user_id', $id)->with('user')->get();
        // dd($data['details']);
        return $data;
    }

    public function update($request, int $id)
    {
        $data = $request->validated();
        $record = $this->model->withTrashed()->findOrFail($id);
        $photoPath = $record->photo;
        if($request->hasFile('photo')){
            if (File::exists(public_path($photoPath))) {
                File::delete(public_path($photoPath));
            }
            $data['photo'] = $this->upload($request->photo);
        }
        $updateUser = $this->model->where('id', $id)->update($data);
        $updated = $this->model->where('id', $id)->first();
        $this->saveUserDetails($updated);
        return $updateUser;
    }

    public function destroy($id)
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        $photoPath = $record->photo;

        if ($record->forceDelete()) {
            if (File::exists(public_path($photoPath))) {
                File::delete(public_path($photoPath));
            }
            return $record;
        }
    }

    public function listTrashed()
    {
        $data['archive'] = $this->model->onlyTrashed()->paginate(2);
        return $data;
    }

    public function restore($id)
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        return $record->restore();
    }

    public function delete($id)
    {
        $delete = $this->model->where('id', $id)->delete();
        return $delete;
    }

    public function upload($file)
    {
        $image = '';

        if ($file) {
            $photoDir = "photos";
            $photo = Str::random(12) .'.'. $file->extension();

            $file->move(public_path($photoDir), $photo);
            $image = $photoDir.'/'.$photo;
            return $image;
        }
    }

    public function hash(string $pwd)
    {
        return Hash::make($pwd);
    }

    public function prefixName(): array
    {
        return [
            "Mr",
            "Mrs",
            "Ms"
        ];
    }

}
