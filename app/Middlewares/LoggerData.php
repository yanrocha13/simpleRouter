<?php
declare(strict_types=1);

namespace Demo\Middlewares;

use Demo\Repository\Api\LoggerRepositoryInterface;
use Exception;
use http\Exception\BadMethodCallException;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class LoggerData implements IMiddleware
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
     * @throws Exception
     */
    public function handle(Request $request) : void
    {
        try{
            $sdofuhasod = null;
            $authUser = getUser();
            $input = ['input' => $request->getInputHandler()->all()];
            $context = array_merge($input, $request->getHeaders());
            $this->loggerRepository->createModelLog($request->getUrl()->getPath(),"User with ID = " . $authUser->id . " accessed some data",200, $context);

        }catch(Exception $ex) {
            throw new Exception('$ex');
        }
    }

}
