<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceCategory extends Model
{
    use HasFactory;
    protected $table = "maint_categories";
    protected $fillable = [
        'type'
    ];
    public function maintenance()
    {
        return $this->hasMany("App\Model\Maintenance", "type_id", "id")->withTrashed();
    }
}
