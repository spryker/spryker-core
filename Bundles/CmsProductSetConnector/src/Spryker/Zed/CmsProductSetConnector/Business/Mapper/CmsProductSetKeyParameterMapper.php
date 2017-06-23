<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsProductSetConnector\Business\Mapper;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;

class CmsProductSetKeyParameterMapper implements CmsProductSetKeyParameterMapperInterface
{

    /**
     * @var \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetConnectorQueryContainerInterface
     */
    protected $cmsProductSetConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsProductSetConnector\Persistence\CmsProductSetConnectorQueryContainerInterface $cmsProductSetQueryContainer
     */
    public function __construct($cmsProductSetQueryContainer)
    {
        $this->cmsProductSetConnectorQueryContainer = $cmsProductSetQueryContainer;
    }

    /**
     * @param array $skuList
     *
     * @return array
     */
    public function mapProductSetKeyList(array $skuList)
    {
        $productSetIds = $this->cmsProductSetConnectorQueryContainer
            ->queryProductSetIdsByKeyList($skuList)
            ->find()
            ->toArray();

        $productSetKeyIdPairs = [];
        foreach ($productSetIds as $id) {
            $productSetKeyIdPairs[$id[SpyProductSetTableMap::COL_PRODUCT_SET_KEY]] = $id[SpyProductSetTableMap::COL_ID_PRODUCT_SET];
        }

        return $productSetKeyIdPairs;
    }

}
