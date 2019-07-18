<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;

class ProductAttributeHelper implements ProductAttributeHelperInterface
{
    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(
        ProductManagementToProductInterface $productFacade,
        ProductQueryContainerInterface $productQueryContainer
    ) {
        $this->productFacade = $productFacade;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int
     */
    public function getProductAbstractSuperAttributesCount(ProductAbstractTransfer $productAbstractTransfer): int
    {
        $combinedAbstractAttributeKeys = $this->productFacade
            ->getCombinedAbstractAttributeKeys($productAbstractTransfer);

        return $this->productQueryContainer
            ->queryProductAttributeKey()
            ->filterByKey_In($combinedAbstractAttributeKeys)
            ->filterByIsSuper(true)
            ->count();
    }
}
