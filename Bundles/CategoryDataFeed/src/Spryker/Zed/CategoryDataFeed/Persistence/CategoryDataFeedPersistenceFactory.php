<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDataFeed\Persistence;

use Spryker\Zed\CategoryDataFeed\CategoryDataFeedDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryDataFeed\CategoryDataFeedConfig getConfig()
 * @method \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainerInterface getQueryContainer()
 */
class CategoryDataFeedPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\CategoryDataFeed\Dependency\QueryContainer\CategoryDataFeedToCategoryInterface
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(CategoryDataFeedDependencyProvider::CATEGORY_QUERY_CONTAINER);
    }
}
