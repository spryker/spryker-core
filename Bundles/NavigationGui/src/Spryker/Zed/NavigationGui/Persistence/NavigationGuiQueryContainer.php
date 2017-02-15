<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiPersistenceFactory getFactory()
 */
class NavigationGuiQueryContainer extends AbstractQueryContainer implements NavigationGuiQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation()
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigation();
    }

}
