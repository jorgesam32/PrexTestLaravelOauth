<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GifsModel extends Model
{
    use HasFactory;

    public function addFavorites($new)
    {
        return DB::table('favorites_gif')->insert($new);;
    }
    public function addActivity($newRegister){
        return DB::table('audit_connections')->insert($newRegister);
    }
}
