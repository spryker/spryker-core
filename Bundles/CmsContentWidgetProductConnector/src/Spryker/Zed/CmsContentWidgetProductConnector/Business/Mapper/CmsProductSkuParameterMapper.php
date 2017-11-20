<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductConnector\Business\Mapper;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorQueryContainerInterface;

class CmsProductSkuParameterMapper implements CmsProductSkuParameterMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorQueryContainerInterface
     */
    protected $cmsProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsContentWidgetProductConnector\Persistence\CmsContentWidgetProductConnectorQueryContainerInterface $cmsProductConnectorQueryContainer
     */
    public function __construct(CmsContentWidgetProductConnectorQueryContainerInterface $cmsProductConnectorQueryContainer)
    {
        $this->cmsProductConnectorQueryContainer = $cmsProductConnectorQueryContainer;
    }

    /**
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductSkuList(array $skuList)
    {
        $productIds = $this->cmsProductConnectorQueryContainer
            ->queryProductIdsBySkuList($skuList)
            ->find()
            ->toArray();

        $skuIdPairs = [];
        foreach ($productIds as $id) {
            $skuIdPairs[$id[SpyProductAbstractTableMap::COL_SKU]] = $id[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
        }

        return $skuIdPairs;
    }
}
