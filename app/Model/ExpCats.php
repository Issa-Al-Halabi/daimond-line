<?php

/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpCats extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = "expense_cat";
    protected $fillable = [
        'name', 'user_id', 'cost', 'frequancy', 'type',
    ];
    public function expense()
    {
        return $this->hasMany("App\Model\Expense", "expense_type", "id")->withTrashed();
    }
}
