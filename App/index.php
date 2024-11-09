<?php
namespace App\Library;

require_once "../vendor/autoload.php";

use App\Library\Infrastructure\Persistence\Connection;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header('Content-Type: application/json');

$connection = Connection::getInstance();
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {

    case 'GET':

        var_dump($connection);

        break;

}
