<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
class NavigationQueryContainer extends AbstractQueryContainer implements NavigationQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigationById($idNavigation)
    {
        return $this->getFactory()
            ->createNavigationQuery()
            ->filterByIdNavigation($idNavigation);
    }

}
