<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\NavigationStorage\Business\NavigationStorageBusinessFactory getFactory()
 */
class NavigationStorageFacade extends AbstractFacade implements NavigationStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return void
     */
    public function publish(array $navigationIds)
    {
        $this->getFactory()->createNavigationStorageWriter()->publish($navigationIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $navigationIds
     *
     * @return void
     */
    public function unpublish(array $navigationIds)
    {
        $this->getFactory()->createNavigationStorageWriter()->unpublish($navigationIds);
    }
}
