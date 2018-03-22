<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitExchangeDetailTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantitySalesUnit() === null) {
                continue;
            }

            $productMeasurementSalesUnitEntity = $this->getProductMeasurementSalesUnit($itemTransfer->getQuantitySalesUnit()->getIdProductMeasurementSalesUnit());
            $itemTransfer->getQuantitySalesUnit()
                ->setPrecision($productMeasurementSalesUnitEntity->getPrecision() ? : $productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getDefaultPrecision())
                ->setConversion($this->getConversion($productMeasurementSalesUnitEntity))
                ->setProductMeasurementUnit(
                    (new ProductMeasurementUnitTransfer())
                    ->setIdProductMeasurementUnit($productMeasurementSalesUnitEntity->getFkProductMeasurementUnit())
                    ->setCode($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getCode())
                    ->setName($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getName())
                    ->setDefaultPrecision($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getDefaultPrecision())
                );

            $itemTransfer->getQuantitySalesUnit()->setValue(
                (int)($itemTransfer->getQuantity() * $itemTransfer->getQuantitySalesUnit()->getConversion() * $itemTransfer->getQuantitySalesUnit()->getPrecision())
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity
     *
     * @return int
     */
    protected function getConversion(SpyProductMeasurementSalesUnit $productMeasurementSalesUnitEntity)
    {
        if ($productMeasurementSalesUnitEntity->getConversion() === null) {
            return $this->getFacade()->getExchangeDetail(
                (new ProductMeasurementUnitExchangeDetailTransfer())
                    ->setFromCode($productMeasurementSalesUnitEntity->getProductMeasurementUnit()->getCode())
                    ->setToCode($productMeasurementSalesUnitEntity->getProductMeasurementBaseUnit()->getProductMeasurementUnit()->getCode())
            )->getConversion();
        }

        return $productMeasurementSalesUnitEntity->getConversion();
    }

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit
     */
    protected function getProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
    {
        // TODO: base unit's measurement unit is accessed directly
        return SpyProductMeasurementSalesUnitQuery::create()
            ->joinWithProductMeasurementUnit()
            ->joinWithProductMeasurementBaseUnit()
            ->filterByIdProductMeasurementSalesUnit($idProductMeasurementSalesUnit)
            ->find()
            ->getFirst();
    }
}
