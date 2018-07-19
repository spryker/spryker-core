<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Item;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface;

class ItemMeasurementUnitDataTranslationExpander implements ItemMeasurementUnitDataTranslationExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        ProductMeasurementUnitToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit()
                && $itemTransfer->getQuantitySalesUnit()->getProductMeasurementUnit()
            ) {
                $quantityMeasurementUnitName = $itemTransfer->getQuantitySalesUnit()
                    ->getProductMeasurementUnit()
                    ->getName();

                $localizedQuantityMeasurementUnitName = $this->glossaryFacade
                    ->translate($quantityMeasurementUnitName);

                $itemTransfer->getQuantitySalesUnit()
                    ->getProductMeasurementUnit()
                    ->setName($localizedQuantityMeasurementUnitName);
            }

            if ($itemTransfer->getQuantitySalesUnit()
                && $itemTransfer->getQuantitySalesUnit()->getProductMeasurementBaseUnit()
                && $itemTransfer->getQuantitySalesUnit()->getProductMeasurementBaseUnit()->getProductMeasurementUnit()
            ) {
                $quantityBaseMeasurementUnitName = $itemTransfer->getQuantitySalesUnit()
                    ->getProductMeasurementBaseUnit()
                    ->getProductMeasurementUnit()
                    ->getName();

                $localizedQuantityBaseMeasurementUnitName = $this->glossaryFacade
                    ->translate($quantityBaseMeasurementUnitName);

                $itemTransfer->getQuantitySalesUnit()
                    ->getProductMeasurementBaseUnit()
                    ->getProductMeasurementUnit()
                    ->setName($localizedQuantityBaseMeasurementUnitName);
            }
        }

        return $cartChangeTransfer;
    }
}
