<?php
namespace App\Library;

require_once "../vendor/autoload.php";

use App\Library\Application\Controllers\BookController;
use App\Library\Application\Controllers\LoanController;
use App\Library\Domain\Repositorys\Implementation\BookRepositoryImpl;
use App\Library\Domain\Repositorys\Implementation\LoanRepositoryImpl;
use App\Library\Domain\Services\BookService;
use App\Library\Domain\Services\LoanService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

header('Content-Type: application/json');

$loanRepository = new LoanRepositoryImpl();
$loanService = new LoanService($loanRepository);
$loanController = new LoanController($loanService);
$bookRepository = new BookRepositoryImpl();
$bookService = new BookService($bookRepository);
$bookController = new BookController($bookService);
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {

    case 'POST':
        $data = json_decode(file_get_contents('php://input'));
        if ($uri === '/loans') {

            $loanController->create($data);

        } elseif ($uri === '/books') {

            $bookController->create($data);

        }
        break;

    case 'GET':
        if (preg_match('/^\/loans\/(\d+)$/', $uri, $match)) {

            $id = $match[1];
            $loanController->getById($id);

        } elseif ($uri === '/loans') {

            $loanController->getAll();

        } elseif (preg_match('/^\/books\/(\d+)$/', $uri, $match)) {

            $id = $match[1];
            $bookController->getById($id);

        } elseif ($uri === '/books') {$bookController->getAll();}
        break;

    case 'PUT':
        if (preg_match('/^\/loans\/(\d+)$/', $uri, $match)) {

            $id = $match[1];
            $data = json_decode(file_get_contents('php://input'));
            $loanController->update($id, $data);

        } elseif (preg_match('/^\/books\/(\d+)$/', $uri, $match)) {

            $id = $match[1];
            $data = json_decode(file_get_contents('php://input'));
            $bookController->update($id, $data);

        }
        break;

    case 'DELETE':
        if (preg_match('/^\/loans\/(\d+)$/', $uri, $match)) {
            
            $id = $match[1];
            $loanController->delete($id);
            
        } elseif (preg_match('/^\/books\/(\d+)$/', $uri, $match)) {
            
            $id = $match[1];
            $bookController->delete($id);
            
        }
        break;

    default:http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
        break;
}
