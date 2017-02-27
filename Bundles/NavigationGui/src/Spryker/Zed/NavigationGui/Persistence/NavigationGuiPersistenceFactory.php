<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\NavigationGui\NavigationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainer getQueryContainer()
 */
class NavigationGuiPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\QueryContainer\NavigationGuiToNavigationInterface
     */
    public function getNavigationQueryContainer()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::QUERY_CONTAINER_NAVIGATION);
    }

    /**
     * @return \Spryker\Zed\NavigationGui\Dependency\QueryContainer\NavigationGuiToCmsInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(NavigationGuiDependencyProvider::QUERY_CONTAINER_CMS);
    }

}
