<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestProductMeasurementUnitsAttributesTransfer;
use Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer;
use Generated\Shared\Transfer\SalesUnitTransfer;

class SalesUnitMapper implements SalesUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer
     * @param \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer
     */
    public function mapProductMeasurementSalesUnitTransferToRestSalesUnitsAttributesTransfer(
        ProductMeasurementSalesUnitTransfer $productMeasurementSalesUnitTransfer,
        RestSalesUnitsAttributesTransfer $restSalesUnitsAttributesTransfer
    ): RestSalesUnitsAttributesTransfer {
        return $restSalesUnitsAttributesTransfer
            ->fromArray($productMeasurementSalesUnitTransfer->toArray(), true)
            ->setMeasurementUnitCode($productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->getCode());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer
    ): RestItemsAttributesTransfer {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getAmountSalesUnit();
        if (!$productMeasurementSalesUnitTransfer) {
            return $restItemsAttributesTransfer;
        }

        $salesUnitTransfer = (new SalesUnitTransfer())
            ->setId($productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit())
            ->setAmount($itemTransfer->getAmount());

        return $restItemsAttributesTransfer->setSalesUnit($salesUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer
     */
    public function mapItemTransferToRestOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
    ): RestOrderItemsAttributesTransfer {
        $productMeasurementSalesUnitTransfer = $itemTransfer->getAmountSalesUnit();
        if (!$productMeasurementSalesUnitTransfer) {
            return $restOrderItemsAttributesTransfer;
        }

        $productMeasurementUnitTransfer = $productMeasurementSalesUnitTransfer->getProductMeasurementUnit();
        $restSalesUnitsAttributesTransfer = (new RestSalesUnitsAttributesTransfer())
            ->fromArray($productMeasurementSalesUnitTransfer->toArray(), true)
            ->setMeasurementUnitCode($productMeasurementUnitTransfer->getCode());
        $restProductMeasurementUnitsAttributesTransfer = (new RestProductMeasurementUnitsAttributesTransfer())
            ->fromArray($productMeasurementUnitTransfer->toArray(), true)
            ->setMeasurementUnitCode($productMeasurementUnitTransfer->getCode());

        return $restOrderItemsAttributesTransfer
            ->setSalesUnit($restSalesUnitsAttributesTransfer)
            ->setMeasurementUnit($restProductMeasurementUnitsAttributesTransfer);
    }
}
