<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class laravel_notifications extends Model
{
	protected $table = 'laravel_notifications';
	protected $fillable = array(
		'username',
		'is_read',
		'is_deleted'
	 );
	public static $rules = array();
}