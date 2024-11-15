<?php
namespace App\Library\Application\Controllers;

use App\Library\Application\Utils\Response;
use App\Library\Domain\Exceptions\BookNotAvailableException;
use App\Library\Domain\Exceptions\ExceedsLoanLimitException;
use App\Library\Domain\Exceptions\ResourceNotFoundException;
use App\Library\Domain\Services\LoanService;

/**
 * Class LoanController
 * 
 * Handles HTTP requests related to loans. 
 */
class LoanController
{
    private LoanService $loanService;

    /**
     * Constructor for the LoanController class.
     * 
     * Initializes the loan service.
     * 
     * @param LoanService $loanService The service for managing loans. 
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Handles the creation of a new loan.
     * 
     * @param object $data The data for creating the loan.
     * @return void
     * @throws BookNotAvailableException If the book is not available.
     * @throws ExceedsLoanLimitException If the user exceeds the loan limit. 
     */
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

    /**
     * Handles the update of an existing loan.
     * 
     * @param int $id The ID of the loan to be updated.
     * @param object $data The data for updating the loan.
     * @return void
     * @throws BookNotAvailableException If the book is not available.
     * @throws ExceedsLoanLimitException If the user exceeds the loan limit. 
     */
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

    /**
     * Handles the retrieval of a loan by its ID.
     * 
     * @param int $idLoan The ID of the loan to retrieve.
     * @return void
     * @throws ResourceNotFoundException If the loan is not found. 
     */
    public function getById(int $idLoan)
    {
        try {
            $result = $this->loanService->getLoanById($idLoan);
            Response::jsonSuccess($result, 200);
        } catch (ResourceNotFoundException $e) {
            Response::jsonError($e->getMessage(), 404);
        }
    }

    /**
     * Handles the retrieval of all loans.
     * 
     * @return void 
     */
    public function getAll()
    {
        $result = $this->loanService->getAllLoans();
        Response::jsonSuccess($result, 200);
    }

    /**
     * Handles the deletion of a loan by its ID.
     * 
     * @param int $idLoan The ID of the loan to delete.
     * @return void 
     */
    public function delete(int $idLoan)
    {
        $result = $this->loanService->delete($idLoan);

        if (!$result) {
            Response::jsonError("error deleting a loan", 400);
        }
        
        Response::noContent();
    }
}
