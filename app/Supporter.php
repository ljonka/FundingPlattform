<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    protected $fillable = ['vorname', 'nachname', 'mail', 'strasse', 'plz', 'ort', 'land', 'beitrag', 'iban'];
}
