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
        $accountTransactionList = $this->accountTransaction->getListByIdDecrypted();
        $fulldata = array_merge($accountTransactionList);


        return $this->twig->render()->render('/transactions/Index.html',$fulldata);
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

    /**
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create(): ?string
    {
        return $this->twig->render()->render('/transactions/Create.html',[]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        $request = input()->all();

        $newTransaction = $this->accountTransaction->defineTransaction($request);
        $executeTransaction = $this->accountTransaction->executeTransaction($newTransaction);
        if($executeTransaction){
            $saveTransaction = $this->accountTransaction->create($newTransaction);
        }

        return response()->json([
            'store' => $executeTransaction
        ]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function edit($id): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function update($id): ?string
    {
        return null;
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function destroy($id): ?string
    {
        return null;
    }
}
