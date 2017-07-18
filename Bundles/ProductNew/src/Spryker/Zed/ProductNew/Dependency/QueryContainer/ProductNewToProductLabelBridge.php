<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductNew\Dependency\QueryContainer;

class ProductNewToProductLabelBridge implements ProductNewToProductLabelInterface
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
     * @param string $labelName
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery
     */
    public function queryProductLabelByName($labelName)
    {
        return $this->productLabelQueryContainer->queryProductLabelByName($labelName);
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryProductAbstractRelationsByIdProductLabel($idProductLabel)
    {
        return $this->productLabelQueryContainer->queryProductAbstractRelationsByIdProductLabel($idProductLabel);
    }

}
