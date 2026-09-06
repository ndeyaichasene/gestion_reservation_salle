<?php  

require_once dirname(__DIR__,2). '/vendor/autoload.php'; 

new App\Model\Salle(); 
new App\Model\Reservation();
echo 'Models OK';