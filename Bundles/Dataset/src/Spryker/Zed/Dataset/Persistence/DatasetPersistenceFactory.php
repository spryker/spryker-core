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
use Spryker\Zed\Dataset\Persistence\Mapper\DatasetMapper;
use Spryker\Zed\Dataset\Persistence\Mapper\DatasetMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Dataset\DatasetConfig getConfig()
 */
class DatasetPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    public function createDatasetQuery(): SpyDatasetQuery
    {
        return SpyDatasetQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumnQuery
     */
    public function createSpyDatasetColumnQuery(): SpyDatasetColumnQuery
    {
        return SpyDatasetColumnQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowQuery
     */
    public function createSpyDatasetRowQuery(): SpyDatasetRowQuery
    {
        return SpyDatasetRowQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetRowColumnValueQuery
     */
    public function createSpyDatasetRowColumnValueQuery(): SpyDatasetRowColumnValueQuery
    {
        return SpyDatasetRowColumnValueQuery::create();
    }

    /**
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributesQuery
     */
    public function createSpyDatasetLocalizedAttributesQuery(): SpyDatasetLocalizedAttributesQuery
    {
        return SpyDatasetLocalizedAttributesQuery::create();
    }

    /**
     * @return \Spryker\Zed\Dataset\Persistence\Mapper\DatasetMapperInterface
     */
    public function createDatasetMapper(): DatasetMapperInterface
    {
        return new DatasetMapper();
    }
}
