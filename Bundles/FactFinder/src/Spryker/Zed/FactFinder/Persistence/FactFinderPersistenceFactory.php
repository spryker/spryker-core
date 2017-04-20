<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Persistence;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToCategoryDataFeedInterface;
use Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToProductAbstractDataFeedInterface;
use Spryker\Zed\FactFinder\FactFinderDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainer getQueryContainer()
 */
class FactFinderPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return FactFinderToProductAbstractDataFeedInterface
     */
    public function getProductAbstractDataFeedQueryContainer()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::PRODUCT_ABSTRACT_DATA_FEED);
    }

    /**
     * @return FactFinderToCategoryDataFeedInterface
     */
    public function getCategoryDataFeedQueryContainer()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::CATEGORY_DATA_FEED);
    }

    /**
     * @return SpyLocaleQuery
     */
    public function getLocaleQuery()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::LOCALE_QUERY);
    }

    /**
     * @return \Spryker\Zed\FactFinder\FactFinderConfig
     */
    public function getZedConfig()
    {
        return $this->getConfig();
    }

}
