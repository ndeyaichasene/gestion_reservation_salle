<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

return function(Capsule $capsule):void{
    if(!$capsule->schema()->hasTable('salles')){
    $capsule->schema()->create('salles',function(Blueprint $table){
        $table->id();
        $table->string('nom',100);
        $table->string('batiment',100);
        $table->integer('capacite');
        $table->enum('type',[
            'cours','informatique','laboratoire','amphitheatre','reunion'
        ]);
        $table->boolean('active')->default(true);
        $table->timestamps();

    });
         echo "table salles cree " . PHP_EOL;
    }else{
        echo "table salles existe deja" . PHP_EOL;
    }
};