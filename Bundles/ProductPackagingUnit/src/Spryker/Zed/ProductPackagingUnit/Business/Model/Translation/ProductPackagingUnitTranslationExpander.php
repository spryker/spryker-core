<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Translation;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface;

class ProductPackagingUnitTranslationExpander implements ProductPackagingUnitTranslationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface
     */
    protected $productMeasurementUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
     */
    public function __construct(
        ProductPackagingUnitToProductMeasurementUnitFacadeInterface $productMeasurementUnitFacade
    ) {
        $this->productMeasurementUnitFacade = $productMeasurementUnitFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getAmountSalesUnit()) {
                continue;
            }
            $this->productMeasurementUnitFacade->translateProductMeasurementSalesUnit($itemTransfer->getAmountSalesUnit());
        }

        return $cartChangeTransfer;
    }
}
