<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\Translation;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Glossary\Business\Exception\MissingTranslationException;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeInterface;

class ProductMeasurementUnitTranslationExpander implements ProductMeasurementUnitTranslationExpanderInterface
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
    public function expandCartChangeItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getQuantitySalesUnit()) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()
                ->requireProductMeasurementUnit();

            $itemTransfer->getQuantitySalesUnit()
                ->getProductMeasurementBaseUnit()
                ->requireProductMeasurementUnit();

            $this->expandSalesUnit($itemTransfer);
            $this->expandBaseUnit($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandSalesUnit(ItemTransfer $itemTransfer): void
    {
        $quantityMeasurementUnitName = $itemTransfer->getQuantitySalesUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $localizedQuantityMeasurementUnitName = $this
            ->translate($quantityMeasurementUnitName);

        $itemTransfer->getQuantitySalesUnit()
            ->getProductMeasurementUnit()
            ->setName($localizedQuantityMeasurementUnitName);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function expandBaseUnit(ItemTransfer $itemTransfer): void
    {
        $quantityBaseMeasurementUnitName = $itemTransfer->getQuantitySalesUnit()
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->getName();

        $localizedQuantityBaseMeasurementUnitName = $this
            ->translate($quantityBaseMeasurementUnitName);

        $itemTransfer->getQuantitySalesUnit()
            ->getProductMeasurementBaseUnit()
            ->getProductMeasurementUnit()
            ->setName($localizedQuantityBaseMeasurementUnitName);
    }

    /**
     * @param string $msg
     *
     * @return string
     */
    protected function translate(string $msg)
    {
        try {
            $localizedMsg = $this->glossaryFacade
                ->translate($msg);
        } catch (MissingTranslationException $e) {
            $localizedMsg = $msg;
        }

        return $localizedMsg;
    }
}
