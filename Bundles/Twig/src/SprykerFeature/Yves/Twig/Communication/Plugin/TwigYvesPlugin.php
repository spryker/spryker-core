<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Twig\Communication\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\Twig\Communication\TwigDependencyContainer;

/**
 * @method TwigDependencyContainer getDependencyContainer()
 */
class TwigYvesPlugin extends AbstractPlugin
{

    /**
     * @param Application $application
     *
     * @return \Twig_Extension
     */
    public function getTwigYvesExtension(Application $application)
    {
        return $this->getDependencyContainer()
            ->createYvesTwigExtension($application);
    }

}
