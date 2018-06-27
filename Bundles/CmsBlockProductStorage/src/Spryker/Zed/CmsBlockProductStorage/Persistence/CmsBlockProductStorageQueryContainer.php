<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStoragePersistenceFactory getFactory()
 */
class CmsBlockProductStorageQueryContainer extends AbstractQueryContainer implements CmsBlockProductStorageQueryContainerInterface
{
    const NAME = 'name';

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery
     */
    public function queryCmsBlockProductStorageByIds(array $productIds)
    {
        return $this->getFactory()
            ->createSpyCmsBlockProductStorageQuery()
            ->filterByFkProductAbstract_In($productIds);
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProducts(array $productIds)
    {
        return $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByFkProductAbstract_In($productIds);
    }

    /**
     * @api
     *
     * @return $this|\Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryAllCmsBlockProducts()
    {
        return $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME);
    }
}
