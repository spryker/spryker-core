<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Persistence;

use Spryker\Zed\FactFinder\FactFinderDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 * @method \Spryker\Zed\FactFinder\Persistence\FactFinderQueryContainer getQueryContainer()
 */
class FactFinderPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToProductAbstractDataFeedInterface
     */
    public function getProductAbstractDataFeedQueryContainer()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::PRODUCT_ABSTRACT_DATA_FEED);
    }

    /**
     * @return \Spryker\Zed\FactFinder\Dependency\Persistence\FactFinderToCategoryDataFeedInterface
     */
    public function getCategoryDataFeedQueryContainer()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::CATEGORY_DATA_FEED);
    }

}
