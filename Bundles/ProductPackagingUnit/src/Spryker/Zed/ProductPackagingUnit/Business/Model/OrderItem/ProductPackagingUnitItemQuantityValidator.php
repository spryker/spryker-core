<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface;

class ProductPackagingUnitItemQuantityValidator implements ProductPackagingUnitItemQuantityValidatorInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface
     */
    protected $salesQuantityFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToSalesQuantityFacadeInterface $salesQuantityFacade
     */
    public function __construct(ProductPackagingUnitToSalesQuantityFacadeInterface $salesQuantityFacade)
    {
        $this->salesQuantityFacade = $salesQuantityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isProductPackagingUnitItemQuantitySplittable(ItemTransfer $itemTransfer): bool
    {
        if (!$this->isPackagingUnit($itemTransfer)) {
            return false;
        }

        return $this->salesQuantityFacade->isItemQuantitySplittable($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isPackagingUnit(ItemTransfer $itemTransfer): bool
    {
        return !empty($itemTransfer->getAmountSalesUnit());
    }
}
