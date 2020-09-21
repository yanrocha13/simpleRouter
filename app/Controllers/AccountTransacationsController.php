<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\AccountTransactions;
use Pecee\Controllers\IResourceController;

class AccountTransacationsController implements IResourceController
{
    /**
     * @var AccountTransactions
     */
    private $transaction;

    public function __construct(AccountTransactions $transaction)
    {
        $this->transaction = $transaction;
    }

    public function index(): ?string
    {
        // TODO: Implement index() method.
    }

    public function show($id): ?string
    {
        // TODO: Implement show() method.
    }

    public function store(): ?string
    {
        // TODO: Implement store() method.
        $request = input()->all();

        $newTransaction = $this->transaction->defineTransaction($request);
        $executeTransaction = $this->transaction->executeTransaction($newTransaction);
    }

    public function create(): ?string
    {
        // TODO: Implement create() method.
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
