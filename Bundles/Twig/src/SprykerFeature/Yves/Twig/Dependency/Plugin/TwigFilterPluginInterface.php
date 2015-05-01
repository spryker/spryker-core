<?php

namespace SprykerFeature\Yves\Twig\Dependency\Plugin;

use Silex\Application;

interface TwigFilterPluginInterface
{
    /**
     * @return \Twig_SimpleFilter[]
     */
    public function getFilters();
}