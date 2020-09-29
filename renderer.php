<?php

namespace Demo\Models;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

Class Renderer
{
    public function render(){
        $loader = new FilesystemLoader(__DIR__ . '/app/views');
        $twig = new Environment($loader, []);

        return $twig;
    }
}