<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer;

class ProductLabelStorageToProductLabelQueryContainerBridge implements ProductLabelStorageToProductLabelQueryContainerInterface
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
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery
     */
    public function queryAllProductLabelProductAbstractRelations()
    {
        return $this->productLabelQueryContainer->queryAllProductLabelProductAbstractRelations();
    }

    /**
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributesQuery
     */
    public function queryAllLocalizedAttributesLabels()
    {
        return $this->productLabelQueryContainer->queryAllLocalizedAttributesLabels();
    }
}
