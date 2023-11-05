<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia, MediaCollections\Models\Media};

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, InteractsWithMedia;

    protected $dates = ['deleted_at'];

    const MEDIA_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaCollection(self::MEDIA_USER)
            ->acceptsMimeTypes(['image/jpeg','image/png'])
            ->singleFile()
            ->useDisk('media')
            ->useFallbackUrl(asset('default-profile.webp'))
            ->useFallbackUrl(asset('default-profile.webp'), 'thumbnail')
            ->useFallbackPath(public_path(asset('default-profile.webp')))
            ->useFallbackPath(public_path(asset('default-profile.webp')), 'thumbnail')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumbnail')
                      ->width(50)
                      ->height(50);
            });
    }

    public function saveMedia()
    {
        $this->addMedia(request()->profile)->toMediaCollection('user');
    }

    protected function password(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  $value,
            set: fn ($value) =>  bcrypt($value),
        );
    }
}
