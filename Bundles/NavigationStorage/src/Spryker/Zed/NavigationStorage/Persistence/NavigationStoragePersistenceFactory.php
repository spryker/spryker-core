<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\NavigationStorage\NavigationStorageDependencyProvider;

/**
 * @method \Spryker\Zed\NavigationStorage\NavigationStorageConfig getConfig()
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface getQueryContainer()
 */
class NavigationStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorageQuery
     */
    public function createSpyNavigationStorageQuery()
    {
        return SpyNavigationStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocalePropelQuery(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Spryker\Zed\NavigationStorage\Dependency\QueryContainer\NavigationStorageToNavigationQueryContainerInterface
     */
    public function getNavigationQueryContainer()
    {
        return $this->getProvidedDependency(NavigationStorageDependencyProvider::QUERY_CONTAINER_NAVIGATION);
    }
}
