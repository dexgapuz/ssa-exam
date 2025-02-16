<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UserService implements UserServiceInterface
{
    protected User $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($id = null)
    {
        $usernameUnique = Rule::unique('users', 'username');
        $emailUnique = Rule::unique('users', 'email');

        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => ['required', 'string', $id !== null ? $usernameUnique->ignore($id) : $usernameUnique],
            'email' => ['required', 'string', 'email', $id !== null ? $emailUnique->ignore($id) : $emailUnique],
            'password' => ['nullable', Password::defaults()],
            'photo' => ['nullable', File::image()->min('3kb')],
            'middlename' => 'nullable',
            'suffixname' => 'nullable',
            'prefixname' => 'nullable'
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return $this->model->paginate(10);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes): Model
    {

        if (!empty($attributes['photo'])) {
            $this->model->photo = $this->upload($attributes['photo']);
        }

        $this->model->prefixname = $attributes['prefixname'];
        $this->model->firstname = $attributes['firstname'];
        $this->model->middlename = $attributes['middlename'];
        $this->model->lastname = $attributes['lastname'];
        $this->model->suffixname = $attributes['suffixname'];
        $this->model->email = $attributes['email'];
        $this->model->username = $attributes['username'];
        $this->model->password = $this->hash($attributes['password']);

        $this->model->save();

        return $this->model;
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        $user = $this->model->findOrFail($id);

        if (!empty($attributes['password'])) {
            $user->password = $this->hash($attributes['password']);
        }

        if (!empty($attributes['photo'])) {
            $oldPhoto = $user->photo;
            $user->photo = $this->upload($attributes['photo']);
            if ($user->photo) {
                Storage::delete($oldPhoto);
            }
        }

        $user->prefixname = $attributes['prefixname'];
        $user->firstname = $attributes['firstname'];
        $user->middlename = $attributes['middlename'];
        $user->lastname = $attributes['lastname'];
        $user->suffixname = $attributes['suffixname'];
        $user->email = $attributes['email'];
        $user->username = $attributes['username'];

        return $user->save();
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id)
    {
        $user = $this->model->findOrFail($id);

        $user->delete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed(): LengthAwarePaginator
    {
        $users = $this->model->onlyTrashed()->paginate(10);

        return $users;
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        $user = $this->model->onlyTrashed()->findOrFail($id);

        $user->restore();
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        $user = $this->model->onlyTrashed()->findOrFail($id);

        $user->forceDelete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return Hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        return $file->store('photo', 'public');
    }

    public function saveDetails(User $user)
    {
        $details = collect($user)->only(['fullname', 'avatar', 'middle_initial', 'gender']);

        $details->each(function (?string $item, string $key) use ($user) {
            $user->details()->create([
                'key' => $key,
                'value' => $item
            ]);
        });
    }
}
