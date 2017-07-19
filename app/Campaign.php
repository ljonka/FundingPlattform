<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supporter;
/*
$table->string('uuid')->unique();
$table->string('name');
$table->string('description')->nullable();
$table->string('image_path')->nullable();
$table->boolean('repeated_campaign')->default(true);
$table->integer('repeat_interval')->default('30');
$table->float('amount');
$table->integer('owner')->nullable();
*/
class Campaign extends Model
{
    protected $fillable = [
      'name', 'description', 'image_path',
      'repeated_campaign', 'repeat_interval', 'amount'
    ];

    public function supporters(){
      return $this->hasMany('App\Supporter');
    }

    public function invitations(){
      return $this->hasMany('App\Invitation');
    }

    public function calculation(){
      $complete = $this->amount;
      $funded = 0;
      $factor = 1;
      $supporters = Supporter::where('campaign_id', $this->id)->get();
      foreach($supporters as $supporter){
        $funded += $supporter->beitrag;
      }
      if($funded > $complete){
        $diff = $funded - $complete;
        $firstFund = (count($supporters) > 0) ? $supporters[0]->beitrag : 0;
        $firstFactor = $firstFund / $funded;
        $firstDiff = $diff * $firstFactor;
        $factor = 1 - (1 * ($firstDiff/$firstFund));
      }

      return (object)[
        'funded' => (100*($funded * $factor)) / $complete,
        'funded_round' => round((100*($funded * $factor)) / $complete, 2),
        'factor' => $factor,
        'singlesupports' => 0,
        'complete' => $complete
      ];
    }
}
