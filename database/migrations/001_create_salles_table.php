<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return function(Capsule $capsule):void{
    $capsule->schema()->create('salles',function($table){
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
};