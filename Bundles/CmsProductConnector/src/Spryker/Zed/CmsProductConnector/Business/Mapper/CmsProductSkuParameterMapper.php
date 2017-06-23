<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductConnector\Business\Mapper;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\CmsProductConnector\Persistence\CmsProductConnectorQueryContainerInterface;

class CmsProductSkuParameterMapper implements CmsProductSkuParameterMapperInterface
{

    /**
     * @var \Spryker\Zed\CmsProductConnector\Persistence\CmsProductConnectorQueryContainerInterface
     */
    protected $cmsProductConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsProductConnector\Persistence\CmsProductConnectorQueryContainerInterface $cmsProductConnectorQueryContainer
     */
    public function __construct(CmsProductConnectorQueryContainerInterface $cmsProductConnectorQueryContainer)
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
