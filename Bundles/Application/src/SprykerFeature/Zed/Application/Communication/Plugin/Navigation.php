<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Communication\Plugin;

use SprykerFeature\Zed\Application\Business\ApplicationFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;

/**
 * Class Navigation
 *
 * @method ApplicationFacade getFacade()
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
        return $this->getFacade()
            ->buildNavigation($pathInfo);
    }

}
