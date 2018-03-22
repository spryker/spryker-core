<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidgetProductSetConnector\Business\Mapper;

use Orm\Zed\ProductSet\Persistence\Map\SpyProductSetTableMap;
use Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface;

class CmsProductSetKeyParameterMapper implements CmsProductSetKeyParameterMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface
     */
    protected $cmsProductSetConnectorQueryContainer;

    /**
     * @param \Spryker\Zed\CmsContentWidgetProductSetConnector\Persistence\CmsContentWidgetProductSetConnectorQueryContainerInterface $cmsProductSetQueryContainer
     */
    public function __construct(CmsContentWidgetProductSetConnectorQueryContainerInterface $cmsProductSetQueryContainer)
    {
        $this->cmsProductSetConnectorQueryContainer = $cmsProductSetQueryContainer;
    }

    /**
     * @param array $keyList
     *
     * @return array
     */
    public function mapProductSetKeyList(array $keyList)
    {
        $productSetIds = $this->cmsProductSetConnectorQueryContainer
            ->queryProductSetIdsByKeyList($keyList)
            ->find()
            ->toArray();

        $productSetKeyIdPairs = [];
        foreach ($productSetIds as $id) {
            $productSetKeyIdPairs[$id[SpyProductSetTableMap::COL_PRODUCT_SET_KEY]] = $id[SpyProductSetTableMap::COL_ID_PRODUCT_SET];
        }

        return $productSetKeyIdPairs;
    }
}
