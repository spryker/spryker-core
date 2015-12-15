<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin;

use Spryker\Zed\Application\Business\ApplicationFacade;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
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
