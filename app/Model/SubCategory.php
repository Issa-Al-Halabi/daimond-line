<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory;


    protected $table = "subcategories";
    protected $metaTable = 'vehicles_meta'; //optional.
    protected $fillable = ['title', 'category_id', 'type_id'];
    public function categories()
    {
        return $this->belongsTo("App\Model\Category", "category_id", "id")->withTrashed();
    }
}
