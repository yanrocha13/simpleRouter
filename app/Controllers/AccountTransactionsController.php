<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Renderer;
use Demo\Repository\Api\AccountTransactionRepositoryInterface;
use Demo\Repository\Api\LoggerRepositoryInterface;
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
     * @var LoggerRepositoryInterface
     */
    private $loggerRepository;

    /**
     * AccountTransacationsController constructor.
     * @param AccountTransactionRepositoryInterface $accountTransaction
     * @param Renderer $twig
     * @param LoggerRepositoryInterface $loggerRepository
     */
    public function __construct(AccountTransactionRepositoryInterface $accountTransaction,
                                Renderer $twig,
                                LoggerRepositoryInterface $loggerRepository)
    {
        $this->accountTransaction = $accountTransaction;
        $this->twig = $twig;
        $this->loggerRepository = $loggerRepository;
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
        $user = getUser();

        return $this->twig->render()->render('/transactions/Index.html',["id" => $user->id]);
    }

    /**
     * @param mixed $id
     * @return string|null
     */
    public function show($id): ?string
    {
        $accountTransactionList = $this->accountTransaction->getListByUserIdDecrypted();

        return response()->json([
            'List' => $accountTransactionList
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
        try {
            $request = input()->all();

            $newTransaction = $this->accountTransaction->defineTransaction($request);
            $executeTransaction = $this->accountTransaction->executeTransaction($newTransaction);
            if($executeTransaction){
                $saveTransaction = $this->accountTransaction->create($newTransaction);
            }

            $message = "Created transaction of type " . $saveTransaction->transaction_type . " from account " . $saveTransaction->account_origin_id . " to " . $saveTransaction->account_destination_id;
            $this->loggerRepository->createModelLog("accountTransaction",$message,200, $request);
            return response()->json([
                'store' => $executeTransaction
            ]);
        }catch(\Exception $ex)
        {
            $message = "Some erro ocurred. More info => " . $ex->getMessage();
            $this->loggerRepository->createModelLog("accountTransactions",$message,400);
            return response()->json([
                'store' => $ex
            ]);
        }
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
