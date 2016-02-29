<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationFacade getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
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
        if (!$this->getConfig()->isNavigationEnabled()) {
            return [];
        }

        return $this->getFacade()
            ->buildNavigation($pathInfo);
    }

}
