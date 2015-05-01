<?php

namespace SprykerFeature\Yves\Twig\Plugin;

use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Twig\TwigDependencyContainer;
use \Twig_Extension;

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
