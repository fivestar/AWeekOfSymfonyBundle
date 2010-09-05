<?php

namespace Bundle\AWeekOfSymfonyBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Routing\Router;
use Symfony\Component\OutputEscaper\Escaper;

class EntryRouterHelper extends Helper
{
    protected $generator;

    public function __construct(Router $router)
    {
        $this->generator = $router->getGenerator();
    }

    public function generate($name, $entry, $parameters = array(), $absolute = false)
    {
        $dummyPath = '0000/00/00/a-week-of-symfony-dummy';
        $parameters['path'] = $dummyPath;
        $uri = $this->generator->generate($name, $parameters, $absolute);

        $uri = str_replace(urlencode($dummyPath), Escaper::unescape($entry)->getPath(), $uri);

        return $uri;
    }

    public function getName()
    {
        return 'entry_helper';
    }
}
