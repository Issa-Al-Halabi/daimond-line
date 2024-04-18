<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "categories";
    protected $metaTable = 'vehicles_meta'; //optional.
    protected $fillable = ['name'];

    public function vehicles()
    {
        return $this->hasMany("App\Model\VehicleModel", "category_id", "id");
    }
    public function subcategory()
    {
        return $this->hasMany("App\Model\SubCategory", "category_id");
    }
}
