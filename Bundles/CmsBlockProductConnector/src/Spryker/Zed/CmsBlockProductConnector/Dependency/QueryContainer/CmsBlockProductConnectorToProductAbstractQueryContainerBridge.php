<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Dependency\QueryContainer;

class CmsBlockProductConnectorToProductAbstractQueryContainerBridge implements CmsBlockProductConnectorToProductAbstractQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productAbstractQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct($productQueryContainer)
    {
        $this->productAbstractQueryContainer = $productQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithName($idLocale)
    {
        return $this->productAbstractQueryContainer
            ->queryProductAbstractWithName($idLocale);
    }
}
