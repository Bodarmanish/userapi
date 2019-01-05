<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

	public function product()
	{
    	return $this->belongsTo(User::class);
	}
     protected $fillable = [
        'name', 'price','user_id',
    ];
    
}
