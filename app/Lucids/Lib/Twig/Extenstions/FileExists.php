<?php

namespace Lucids\Lib\Twig\Extenstions;

use Slim\Views\TwigExtension;

class FileExists extends TwigExtension{

	public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('file_exists', 'file_exists'),
        );
    }

    public function getName()
    {
        return 'app_file';
    }

}