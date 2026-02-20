<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Definicije role
     */
    public const ROLE_ADMIN = 1;
    public const ROLE_GRADJANIN = 0;

    public function isAdmin(): bool
    {
        return $this->role == self::ROLE_ADMIN;
        
      
    }

    public function isGradjanin(): bool
    {
        return $this->role == self::ROLE_GRADJANIN;
    }

    public function getRoleTextAttribute(): string
    {
        return $this->isAdmin() ? 'Administrator' : 'Građanin';
    }
   

    /**
     * Relacija sa prijavljenim problemima
     */
    public function reportedIssues()
    {
        return $this->hasMany(ReportedIssue::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}