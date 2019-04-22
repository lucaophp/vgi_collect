<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class User extends Model{
	protected $table = 'user';
    public $timestamps = false;
    protected $fillable = [
        'name','email','password','type','CPF'
    ];
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
