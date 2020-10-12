<?php

namespace Demo\Models;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

Class Renderer
{
    public function render(){
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $twig = new Environment($loader, []);

        return $twig;
    }
}