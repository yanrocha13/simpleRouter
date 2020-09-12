<?php
declare(strict_types=1);

namespace Demo\Controllers;

use Demo\Models\Users;
use Pecee\Controllers\IResourceController;
use Pecee\Http\Request;
use Symfony\Component\Translation\Exception\ExceptionInterface;
use Twig\Environment;

class UsersController implements IResourceController
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->twig = require(__DIR__ . '/../../renderer.php');
    }

    /**
     * @return string|null
     */
    public function create(): ?string
    {
        return response()->json([
            'method' => input()->all()
        ]);
    }

    /**
     * @return string|null
     */
    public function store(): ?string
    {
        try{
            Users::create(input()->all());
            return response()->json([
                'method' => 'store'
            ]);
        }catch (ExceptionInterface $ex){
            return response()->json([
                'method' => $ex
            ]);
        }
    }

    public function index(): ?string
    {
        // TODO: Implement index() method.
    }

    public function show($id): ?string
    {
        // TODO: Implement show() method.
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
