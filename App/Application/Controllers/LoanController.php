<?php
namespace App\Library\Application\Controllers;

use App\Library\Application\Utils\Response;
use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Services\LoanService;

class LoanController
{
    private LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function create($data)
    {
        try {
            $result = $this->loanService->create($data);
            Response::jsonSuccess($result, 201, "loan created successfully");
        } catch (BookNotAvailableException $e) {
            Response::jsonError($e->getMessage(), 400);
        } catch (ExceedsLoanLimitException $e) {
            Response::jsonError($e->getMessage(), 400);
        }
    }

    public function update($id, $data)
    {
        try {
            $result = $this->loanService->update($id, $data);
            Response::jsonSuccess($result, 200, "loan updated successfully");
        } catch (BookNotAvailableException $e) {
            Response::jsonError($e->getMessage(), 400);
        } catch (ExceedsLoanLimitException $e) {
            Response::jsonError($e->getMessage(), 400);
        }
    }

    public function getById(int $idLoan)
    {
        try {
            $result = $this->loanService->getLoanById($idLoan);
            Response::jsonSuccess($result, 200);
        } catch (ResourceNotFoundException $e) {
            Response::jsonError($e->getMessage(), 404);
        }
    }

    public function getAll()
    {
        $result = $this->loanService->getAllLoans();
        Response::jsonSuccess($result, 200);
    }

    public function delete(int $idLoan)
    {
        $result = $this->loanService->delete($idLoan);

        if (!$result) {
            Response::jsonError("error deleting a loan", 400);
        }
        
        Response::noContent();
    }
}
