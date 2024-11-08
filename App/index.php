<?php
namespace App\Library;

require_once "../vendor/autoload.php";

use App\Library\Domain\Entities\Professor;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {

    case 'GET':

        $professor = new Professor();

        break;

}
