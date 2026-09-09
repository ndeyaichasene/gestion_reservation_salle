<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return function(Capsule $capsule):void{
    if(!$capsule->schema()->hasTable('reservations')){
    $capsule->schema()->create('reservations',function($table){
        $table->id();
        $table->foreignId('salle_id')->constrained('salles');
        $table->string('responsable',120);
        $table->string('email',120);
        $table->string('motif',255);
        $table->dateTime('date_debut');
        $table->dateTime('date_fin');
        $table->enum('statut',['confirmee','annulee']);
        $table->timestamps();

    });
        echo "table reservations cree " . PHP_EOL;
    }else{
        echo "table reservations existe deja" . PHP_EOL;
    }
};