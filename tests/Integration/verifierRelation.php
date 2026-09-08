<?php

require_once dirname(__DIR__,2). '/vendor/autoload.php'; 
$bootDatabase = require_once dirname(__DIR__,2).'/config/database.php'; 
$bootDatabase(); 

$salle = new App\Model\Salle(); 

$reservation = new App\Model\Reservation(); 
var_dump($salle->reservations() instanceof Illuminate\Database\Eloquent\Relations\HasMany);
var_dump($reservation->salle() instanceof Illuminate\Database\Eloquent\Relations\BelongsTo);