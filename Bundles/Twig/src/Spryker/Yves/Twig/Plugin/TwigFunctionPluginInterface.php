<?php

namespace Spryker\Yves\Twig\Plugin;

use Silex\Application;

interface TwigFunctionPluginInterface
{

    /**
     * @param \Silex\Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application);

}