<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey ='staff_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneNo',
        'role'
    ];

    protected $hidden =[
        'password',
    ];

    public static function checkCredentials (string $email,string $password)
    {
        $staff = self::where('email', $email)->first();

        if($staff && $staff->password === $password){
            return $staff;
        }

        return null;
    }
}
