<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Plugin;

use Spryker\Zed\Application\Business\ApplicationFacade;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Application\Communication\ApplicationCommunicationFactory;

/**
 * @method ApplicationFacade getFacade()
 * @method ApplicationCommunicationFactory getFactory()
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
