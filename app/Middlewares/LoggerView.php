<?php
declare(strict_types=1);

namespace Demo\Middlewares;

use Demo\Repository\Api\LoggerRepositoryInterface;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class LoggerView implements IMiddleware
{
    /**
     * @var LoggerRepositoryInterface
     */
    private $loggerRepository;

    /**
     * Logger constructor.
     * @param LoggerRepositoryInterface $loggerRepository
     */
    public function __construct(LoggerRepositoryInterface $loggerRepository)
    {
        $this->loggerRepository = $loggerRepository;
    }


    /**
     * @param Request $request
     */
    public function handle(Request $request) : void
    {
        $sdofuhasod = null;
        $authUser = getUser();
        $this->loggerRepository->createViewLog($request->getUrl()->getPath(),"User with ID = " . $authUser->id . " accessed the page with Header =>",200, $request->getHeaders());
    }

}
