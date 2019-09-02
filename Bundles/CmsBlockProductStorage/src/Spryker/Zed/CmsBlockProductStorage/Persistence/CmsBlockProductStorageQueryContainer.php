<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Persistence;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Persistence\CmsBlockProductStoragePersistenceFactory getFactory()
 */
class CmsBlockProductStorageQueryContainer extends AbstractQueryContainer implements CmsBlockProductStorageQueryContainerInterface
{
    public const NAME = 'name';
    protected const KEY = 'key';

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return \Orm\Zed\CmsBlockProductStorage\Persistence\SpyCmsBlockProductStorageQuery
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
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProducts(array $productIds)
    {
        $query = $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME);

        if (defined('\Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap::COL_KEY')) {
            $query->withColumn(SpyCmsBlockTableMap::COL_KEY, static::KEY);
        }

        return $query->filterByFkProductAbstract_In($productIds);
    }

    /**
     * @api
     *
     * @param int[] $cmsBlockProductIds
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductsByIds(array $cmsBlockProductIds): SpyCmsBlockProductConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->innerJoinCmsBlock()
            ->withColumn(SpyCmsBlockTableMap::COL_NAME, static::NAME)
            ->filterByIdCmsBlockProductConnector_In($cmsBlockProductIds);
    }

    /**
     * Specification:
     * - Returns a a query for the table `spy_cms_block_product_connector` filtered by cms block product ids.
     *
     * @api
     *
     * @param int[] $cmsBlockProductIds
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductsByCmsBlockProductIds(array $cmsBlockProductIds): SpyCmsBlockProductConnectorQuery
    {
        return $this->getFactory()
            ->getCmsBlockProductConnectorQuery()
            ->queryCmsBlockProductConnector()
            ->filterByIdCmsBlockProductConnector_In($cmsBlockProductIds);
    }
}
