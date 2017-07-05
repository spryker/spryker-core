<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Dependency\QueryContainer;

class ProductLabelDiscountConnectorToProductLabelBridge implements ProductLabelDiscountConnectorToProductLabelInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $productLabelQueryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $productLabelQueryContainer
     */
    public function __construct($productLabelQueryContainer)
    {
        $this->productLabelQueryContainer = $productLabelQueryContainer;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryValidProductLabelsByIdProductAbstract($idProductAbstract)
    {
        return $this->productLabelQueryContainer->queryValidProductLabelsByIdProductAbstract($idProductAbstract);
    }

}
