<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Repository\Api\AccountTransactionRepositoryInterface;
use Pecee\Controllers\IResourceController;

class AccountTransacationsController implements IResourceController
{
    /**
     * @var AccountTransactionRepositoryInterface
     */
    private $accountTransaction;

    public function __construct(AccountTransactionRepositoryInterface $accountTransaction)
    {
        $this->accountTransaction = $accountTransaction;
    }

    public function index(): ?string
    {
        $accountTransactionList = $this->accountTransaction->list();
        return response()->json([
            'index' => $accountTransactionList
        ]);
    }

    public function show($id): ?string
    {
        $accountTransaction = $this->accountTransaction->find($id);
        return response()->json([
            'show' => $accountTransaction
        ]);
    }

    public function create(): ?string
    {
        return response()->json([
            'create' => "return blade"
        ]);
    }

    public function store(): ?string
    {
        // TODO: Implement store() method.
        $request = input()->all();

        $newTransaction = $this->accountTransaction->defineTransaction($request);
        $executeTransaction = $this->accountTransaction->executeTransaction($newTransaction);
    }

    public function edit($id): ?string
    {
        // TODO: Implement edit() method.
    }

    public function update($id): ?string
    {
        // TODO: Implement update() method.
    }

    public function destroy($id): ?string
    {
        // TODO: Implement destroy() method.
    }
}
