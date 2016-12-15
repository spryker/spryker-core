<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Application\Business\ApplicationBusinessFactory getFactory()
 */
class ApplicationFacade extends AbstractFacade implements ApplicationFacadeInterface
{

    /**
     * @api
     *
     * @param string $pathInfo
     *
     * @return array
     */
    public function buildNavigation($pathInfo)
    {
        return $this->getFactory()->createNavigationBuilder()->build($pathInfo);
    }

    /**
     * @api
     *
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getFactory()->createNavigationCacheBuilder()->writeNavigationCache();
    }

}
