<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendBook extends Model
{
    use HasFactory;

    protected $table = 'lend_book';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identification',
        'isbn',
        'observations',
        'deadline',
        'returned',
        'user_id',
        'book_id'
    ];
}
