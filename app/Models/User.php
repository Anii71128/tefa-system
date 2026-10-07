<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'guru_id');
    }

    public function productionStudents(): HasMany
    {
        return $this->hasMany(ProductionStudent::class, 'student_id');
    }

    public function qualityControls(): HasMany
    {
        return $this->hasMany(QualityControl::class, 'checked_by');
    }

    public function evaluationsAsStudent(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'student_id');
    }

    public function evaluationsAsEvaluator(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluated_by');
    }
}
