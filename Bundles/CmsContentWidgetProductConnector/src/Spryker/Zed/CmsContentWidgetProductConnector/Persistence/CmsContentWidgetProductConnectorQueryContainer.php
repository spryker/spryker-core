<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorPersistenceFactory getFactory()
 */
class CmsContentWidgetProductConnectorQueryContainer extends AbstractQueryContainer implements CmsContentWidgetProductConnectorQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string[] $skuList
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductIdsBySkuList(array $skuList)
    {
        return $this->getFactory()
            ->getProductQueryContainer()
            ->queryProductAbstract()
            ->select([
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractTableMap::COL_SKU,
            ])
            ->filterBySku($skuList, Criteria::IN);
    }
}
