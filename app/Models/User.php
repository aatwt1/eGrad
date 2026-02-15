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

    /**
     * Provjera da li korisnik ima određenu rolu
     * role = 1 → admin
     * role = 0 → građanin (default)
     */
    public function hasRole($role): bool
    {
        // Ako je string 'admin' ili 'građanin'
        if ($role === 'admin' || $role === 'administrator') {
            return $this->role == self::ROLE_ADMIN; // 1
        }
        
        if ($role === 'građanin' || $role === 'gradjanin' || $role === 'user') {
            return $this->role == self::ROLE_GRADJANIN; // 0
        }
        
        // Ako je broj (1 ili 0)
        if (is_numeric($role)) {
            return $this->role == (int) $role;
        }
        
        return false;
    }

    /**
     * Provjera da li je korisnik admin
     */
    public function isAdmin(): bool
    {
        return $this->role == self::ROLE_ADMIN; // 1
    }

    /**
     * Provjera da li je korisnik građanin
     */
    public function isGradjanin(): bool
    {
        return $this->role == self::ROLE_GRADJANIN; // 0
    }

    /**
     * Provjera da li korisnik ima permisiju
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = [
            '1' => ['manage_users', 'publish_news', 'approve_initiatives'],
            '0' => ['create_initiative', 'vote_initiative', 'report_issue'],
        ];

        $role = $this->role ?? '0'; // default

        return in_array($permission, $permissions[$role] ?? []);
    }

    /**
     * Accessor za admin provjeru
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Accessor za građanin provjeru
     */
    public function getIsGradjaninAttribute(): bool
    {
        return $this->isGradjanin();
    }

    /**
     * Get sve permisije korisnika
     */
    public function getAllPermissionsAttribute(): array
    {
        $permissions = [
            '1' => ['manage_users', 'publish_news', 'approve_initiatives'],
            '0' => ['create_initiative', 'vote_initiative', 'report_issue'],
        ];
        
        $role = $this->role ?? '0';
        return $permissions[$role] ?? [];
    }

    /**
     * Mutator za role - osigurava da je uvijek string za permisije
     */
    public function getRoleAttribute($value)
    {
        // Vraća kao string za permisije array ključ
        return (string) $value;
    }

    /**
     * Accessor za role tekst
     */
    public function getRoleTextAttribute(): string
    {
        return $this->isAdmin() ? 'Administrator' : 'Građanin';
    }

    /**
     * Helper metode za specifične permisije
     */
    public function canManageUsers(): bool
    {
        return $this->hasPermission('manage_users');
    }

    public function canPublishNews(): bool
    {
        return $this->hasPermission('publish_news');
    }

    public function canApproveInitiatives(): bool
    {
        return $this->hasPermission('approve_initiatives');
    }

    public function canCreateInitiative(): bool
    {
        return $this->hasPermission('create_initiative');
    }

    public function canVoteInitiative(): bool
    {
        return $this->hasPermission('vote_initiative');
    }

    public function canReportIssue(): bool
    {
        return $this->hasPermission('report_issue');
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'is_admin',
        'is_gradjanin',
        'role_text',
        'all_permissions',
    ];
}