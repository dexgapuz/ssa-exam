<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Events\UserSaved;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'email',
        'username',
        'password',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['fullname', 'avatar', 'middle_initial', 'gender'];

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    public function details(): HasMany
    {
        return $this->hasMany(Detail::class);
    }

    protected function middleInitial(): Attribute
    {
        return Attribute::make(get: function (mixed $value, array $attributes) {
            return !empty($attributes['middlename']) ? $this->convertMiddleInitial($attributes['middlename']) : null;
        });
    }

    protected function fullname(): Attribute
    {
        return Attribute::make(get: function (mixed $value, array $attributes) {
            $middleInitial = !empty($attributes['middlename']) ? $this->convertMiddleInitial($attributes['middlename']) : null;

            return ucfirst($attributes['firstname']) . ' ' . $middleInitial . ' ' . ucfirst($attributes['lastname']);
        });
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(get: fn(mixed $value, array $attributes) => isset($attributes['photo']) ? Storage::url($attributes['photo']) : null);
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $gender = null;

                if (isset($attributes['prefixname'])) {
                    $gender = in_array($attributes['prefixname'], ['Ms.', 'Mrs.']) ? 'Female' : 'Male';
                }

                return $gender;
            }
        );
    }

    private function convertMiddleInitial($value): string
    {
        return ucfirst(substr($value, 0, 1)) . '.';
    }
}
