<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Persistence;

use Orm\Zed\Dataset\Persistence\SpyDatasetColumnQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributesQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValueQuery;
use Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface getQueryContainer()
 */
class DatasetPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function createDatasetQuery()
    {
        return SpyDatasetQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumnQuery
     */
    public function createSpyDatasetColumnQuery()
    {
        return SpyDatasetColumnQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery
     */
    public function createSpyDatasetRowQuery()
    {
        return SpyDatasetRowQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValueQuery
     */
    public function createSpyDatasetRowColumnValueQuery()
    {
        return SpyDatasetRowColumnValueQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributesQuery
     */
    public function createSpyDatasetLocalizedAttributesQuery()
    {
        return SpyDatasetLocalizedAttributesQuery::create();
    }
}
