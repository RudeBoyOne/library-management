<?php
namespace App\Library;

require_once "../vendor/autoload.php";

use App\Library\Application\Controllers\LoanController;
use App\Library\Domain\Repositorys\Implementation\LoanRepositoryImpl;
use App\Library\Domain\Services\LoanService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header('Content-Type: application/json');

$loanRepository = new LoanRepositoryImpl();
$loanService = new LoanService($loanRepository);
$loanController = new LoanController($loanService);
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {

    case 'POST':

        if ($uri === '/loans') {
            $data = json_decode(file_get_contents('php://input'));

            $loanController->create($data);
        }

        break;

    case 'GET':

        if ($uri === '/loans') {
            $loanController->getAll();
        }

        if (preg_match('/\/loans\/(\d+)/', $uri, $match)) {
            $id = $match[1];
            $loanController->getById($id);
        }

        break;

    case 'PUT':
        if (preg_match('/\/loans\/(\d+)/', $uri, $match)) {
            $id = $match[1];
            $data = json_decode(file_get_contents('php://input'));
            $loanController->update($id, $data);
        }
        break;

    case 'DELETE':
        if (preg_match('/\/loans\/(\d+)/', $uri, $match)) {
            $id = $match[1];
            $loanController->delete($id);
        }
        break;
}
