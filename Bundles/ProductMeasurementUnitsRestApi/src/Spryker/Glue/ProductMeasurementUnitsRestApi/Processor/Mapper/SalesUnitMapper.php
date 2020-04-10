<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\RestCartItemsSalesUnitAttributesTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersProductMeasurementUnitsAttributesTransfer;
use Generated\Shared\Transfer\RestOrdersSalesUnitAttributesTransfer;
use Generated\Shared\Transfer\RestSalesUnitsAttributesTransfer;

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
            ->setProductMeasurementUnitCode($productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->getCode());
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

        $restCartItemsSalesUnitAttributesTransfer = (new RestCartItemsSalesUnitAttributesTransfer())
            ->setId($productMeasurementSalesUnitTransfer->getIdProductMeasurementSalesUnit())
            ->setAmount($itemTransfer->getAmount());

        return $restItemsAttributesTransfer->setSalesUnit($restCartItemsSalesUnitAttributesTransfer);
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

        $restProductMeasurementUnitsAttributesTransfer = (new RestOrdersProductMeasurementUnitsAttributesTransfer())
            ->fromArray($productMeasurementSalesUnitTransfer->getProductMeasurementUnit()->toArray(), true);

        $restOrdersSalesUnitAttributesTransfer = (new RestOrdersSalesUnitAttributesTransfer())
            ->fromArray($productMeasurementSalesUnitTransfer->toArray(), true)
            ->setProductMeasurementUnit($restProductMeasurementUnitsAttributesTransfer);

        return $restOrderItemsAttributesTransfer
            ->setAmount($itemTransfer->getAmount())
            ->setSalesUnit($restOrdersSalesUnitAttributesTransfer);
    }
}
