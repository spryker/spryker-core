<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

/**
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiPersistenceFactory getFactory()
 */
interface NavigationGuiQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation();

}
