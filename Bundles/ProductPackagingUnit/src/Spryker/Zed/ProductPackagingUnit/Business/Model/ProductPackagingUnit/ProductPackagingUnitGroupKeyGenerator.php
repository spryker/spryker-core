<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface;

class ProductPackagingUnitGroupKeyGenerator implements ProductPackagingUnitGroupKeyGeneratorInterface
{
    protected const AMOUNT_GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';

    /**
     * @var \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface
     */
    protected $service;

    /**
     * @param \Spryker\Service\ProductPackagingUnit\ProductPackagingUnitServiceInterface $service
     */
    public function __construct(ProductPackagingUnitServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function getItemWithGroupKey(ItemTransfer $itemTransfer): string
    {
        if (!$itemTransfer->getAmountSalesUnit() || !$itemTransfer->getAmount()) {
            return $itemTransfer->getGroupKey();
        }

        $amountPerQuantity = $itemTransfer->getAmount() / $itemTransfer->getQuantity();

        return sprintf(
            static::AMOUNT_GROUP_KEY_FORMAT,
            $itemTransfer->getGroupKey(),
            $this->service->round($amountPerQuantity),
            $itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit()
        );
    }
}
