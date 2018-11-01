<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Dependency\Facade;

use Generated\Shared\Transfer\ItemTransfer;

class ProductPackagingUnitToSalesQuantityFacadeBridge implements ProductPackagingUnitToSalesQuantityFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface
     */
    protected $salesQuantityFacade;

    /**
     * @param \Spryker\Zed\SalesQuantity\Business\SalesQuantityFacadeInterface $salesQuantityFacade
     */
    public function __construct($salesQuantityFacade)
    {
        $this->salesQuantityFacade = $salesQuantityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemQuantitySplittable(ItemTransfer $itemTransfer)
    {
        return $this->salesQuantityFacade->isItemQuantitySplittable($itemTransfer);
    }
}
