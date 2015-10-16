<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin;

use SprykerFeature\Zed\Application\Communication\ApplicationDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Class Navigation
 *
 * @method ApplicationDependencyContainer getDependencyContainer()
 */
class Navigation extends AbstractPlugin
{

    /**
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getDependencyContainer()
            ->getApplicationFacade()
            ->buildNavigation($pathInfo)
        ;
    }

}
