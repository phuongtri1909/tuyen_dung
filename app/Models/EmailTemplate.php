<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'subject', 'content'];

    /**
     * Lấy mẫu email theo vai trò
     */
    public static function getByRole($role)
    {
        return self::where('role', $role)->first();
    }
}