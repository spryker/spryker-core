<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Twig\Communication\Dependency\Plugin;

use Silex\Application;

interface TwigFunctionPluginInterface
{

    /**
     * @param Application $application
     *
     * @return \Twig_SimpleFunction[]
     */
    public function getFunctions(Application $application);

}
