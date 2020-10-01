<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Renderer;
use Demo\Repository\Api\AccountTransactionRepositoryInterface;
use Pecee\Controllers\IResourceController;

class AccountTransactionsController implements IResourceController
{
    /**
     * @var AccountTransactionRepositoryInterface
     */
    private $accountTransaction;

    /**
     * @var Renderer
     */
    private $twig;

    /**
     * AccountTransacationsController constructor.
     * @param AccountTransactionRepositoryInterface $accountTransaction
     * @param Renderer $twig
     */
    public function __construct(AccountTransactionRepositoryInterface $accountTransaction,
                                Renderer $twig)
    {
        $this->accountTransaction = $accountTransaction;
        $this->twig = $twig;
    }

    /**
     * @return string|null
     */
    public function index(): ?string
    {
        $accountTransactionList = $this->accountTransaction->list();
        return response()->json([
            'index' => $accountTransactionList
        ]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function viewIndex()
    {
        $userId = 5;
        $accountTransactionList = $this->accountTransaction->listById($userId);

//        $fulldata = array_merge($accountTransactionList);


        return $this->twig->render()->render('/transactions/Index.html',compact('accountTransactionList'));
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $accountTransaction = $this->accountTransaction->find($id);
        return response()->json([
            'show' => $accountTransaction
        ]);
    }

    public function create(): ?string
    {
        return $this->twig->render()->render('/transactions/Create.html',[]);
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
