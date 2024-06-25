<?php
//App/Twig/BasePathExtension.php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BasePathExtension extends AbstractExtension
{
    private $basePath;

    public function __construct()
    {
        $this->basePath = dirname($_SERVER['SCRIPT_NAME']);
    }

    public function getFunctions()
    {
        return [
            new TwigFunction("basePath", [$this, "getBasePath"]),
        ];
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
