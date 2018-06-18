<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business\Cart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductFacadeInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\SalesQuantity\Dependency\Facade\SalesQuantityToProductFacadeInterface $productFacade
     */
    public function __construct(
        SalesQuantityToProductFacadeInterface $productFacade
    ) {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandWithIsQuantitySplittable(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConcreteTransfer = $this->productFacade->getProductConcrete($itemTransfer->getSku());
            $this->assertProductConcreteTransfer($productConcreteTransfer);
            $this->expandItemWithProductConcrete($productConcreteTransfer, $itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandItemWithProductConcrete(ProductConcreteTransfer $productConcreteTransfer, ItemTransfer $itemTransfer): void
    {
        $itemTransfer->setIsQuantitySplittable($productConcreteTransfer->getIsQuantitySplittable());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function assertProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productConcreteTransfer
            ->requireIsQuantitySplittable();
    }
}
