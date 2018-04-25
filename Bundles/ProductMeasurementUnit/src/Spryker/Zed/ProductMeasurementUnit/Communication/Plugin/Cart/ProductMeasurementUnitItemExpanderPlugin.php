<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\Cart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnit\Communication\ProductMeasurementUnitCommunicationFactory getFactory()
 */
class ProductMeasurementUnitItemExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $itemTransfer->getQuantitySalesUnit()->requireIdProductMeasurementSalesUnit();

            $salesUnitEntity = $this->getFacade()->getSalesUnitEntity($itemTransfer->getQuantitySalesUnit()->getIdProductMeasurementSalesUnit());
            $salesUnitTransfer = $this->mapToProductMeasurementSalesUnit($salesUnitEntity);
            $itemTransfer->setQuantitySalesUnit($salesUnitTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    protected function mapToProductMeasurementSalesUnit(
        SpyProductMeasurementSalesUnitEntityTransfer $salesUnitEntityTransfer
    ): ProductMeasurementSalesUnitTransfer {
        return (new ProductMeasurementSalesUnitTransfer())
            ->fromArray($salesUnitEntityTransfer->toArray(true), true);
    }
}
