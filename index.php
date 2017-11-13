<?php
define('TOMBOLA', true);
define('DS', DIRECTORY_SEPARATOR);
require_once 'class' . DS . 'database.php';
require_once 'class' . DS . 'partita.php';
require_once 'class' . DS . 'numeri.php';

$d = database::getInstance();
$n = new numeri();
$a = 0;
do
{
    $num = $n->getNuovoNumero();
    var_dump($num);
    $a++;
}
while ($a < 90);
?>