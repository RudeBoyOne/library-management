<?php
namespace App\Library\Domain\Repositorys;

use App\Library\Domain\Entities\Loan;

/**
 * Interface LoanRepository
 * 
 * Defines the operations for managing loans. 
 * 
 * */
interface LoanRepository
{
    /**
     * Creates a new loan.
     * 
     * @param Loan $loan The loan to be created.
     * @return bool Returns true if the loan was successfully created, false otherwise. 
     * */
    public function createLoan(Loan $loan): bool; 
    
    /**
     * Updates an existing loan.
     * 
     * @param Loan $loan The loan to be updated.
     * @return bool Returns true if the loan was successfully updated, false otherwise. 
     * */
    public function updateLoan(Loan $loan): bool; 
    
    /**
     * Retrieves a loan by its ID.
     * 
     * @param int $idLoan The ID of the loan to retrieve.
     * @return Loan Returns the loan if found. 
     * */
    public function getLoanById(int $idLoan): Loan; 
    
    /** 
     * Retrieves the books associated with a loan.
     * 
     * @param int $idLoan The ID of the loan.
     * @return array Returns an array of books associated with the loan. 
     * 
     * */
    public function toPickUpBooksFromALoan(int $idLoan): array; 
    
    /** 
     * Retrieves all loans.
     * 
     *  @return array Returns an array of all loans. 
     * */
    public function getAllLoans(): array; 
    
    /**
     * Deletes a loan by its ID.
     * 
     * @param int $idLoan The ID of the loan to delete.
     * @return bool Returns true if the loan was successfully deleted, false otherwise. 
     * */
    public function deleteLoan(int $idLoan): bool; 
    
    /**
     * Gets the number of copies of a book that are currently on loan.
     * 
     * @param int $idBook The ID of the book.
     * @return int Returns the number of copies of the book that are on loan. 
     * */
    public function howManyCopiesOfABookAreOnLoan(int $idBook): int; 
    
    /**
     * Gets the number of loans a user currently has.
     * 
     * @param int $idUser The ID of the user.
     * @return int Returns the number of loans the user has. 
     * */
    public function howManyLoansDoesAUserHave(int $idUser): int;}
