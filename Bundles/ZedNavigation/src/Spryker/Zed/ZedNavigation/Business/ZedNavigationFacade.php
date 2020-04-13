<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationBusinessFactory getFactory()
 */
class ZedNavigationFacade extends AbstractFacade implements ZedNavigationFacadeInterface
{
    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function writeNavigationCache()
    {
        $this->getFactory()->createNavigationCacheBuilder()->writeNavigationCache();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function removeNavigationCache(): void
    {
        $this->getFactory()->createNavigationCacheRemover()->removeNavigationCache();
    }
}
