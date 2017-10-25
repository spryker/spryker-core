<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorPersistenceFactory getFactory()
 */
class CmsBlockProductConnectorQueryContainer extends AbstractQueryContainer implements CmsBlockProductConnectorQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnector()
    {
        return $this->getFactory()
            ->createCmsBlockProductConnectorQuery();
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockProductConnector()
            ->filterByFkCmsBlock($idCmsBlock);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorWithNameByIdCmsBlock($idCmsBlock, $idLocale)
    {
        return $this->queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock)
            ->joinProductAbstract()
            ->useProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                    ->endUse()
                ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, static::COL_PRODUCT_ABSTRACT_NAME)
                ->withColumn(SpyProductAbstractTableMap::COL_SKU, static::COL_PRODUCT_ABSTRACT_SKU)
                ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdProductAbstract($idProductAbstract)
    {
        return $this->queryCmsBlockProductConnector()
            ->filterByFkProductAbstract($idProductAbstract);
    }
}
