<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    public function book() {
        return $this->belongsTo(Book::class);
    }

    protected $fillable = [
        'loan_date',
        'return_date',
        'book_id'
    ];
}
