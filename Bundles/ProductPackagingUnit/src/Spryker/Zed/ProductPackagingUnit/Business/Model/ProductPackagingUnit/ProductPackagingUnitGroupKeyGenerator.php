<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface;

class ProductPackagingUnitGroupKeyGenerator implements ProductPackagingUnitGroupKeyGeneratorInterface
{
    protected const AMOUNT_GROUP_KEY_FORMAT = '%s_amount_%s_sales_unit_id_%s';

    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface
     */
    protected $utilPriceService;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Service\ProductPackagingUnitToUtilPriceServiceInterface $utilPriceService
     */
    public function __construct(ProductPackagingUnitToUtilPriceServiceInterface $utilPriceService)
    {
        $this->utilPriceService = $utilPriceService;
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
            $amountPerQuantity,
            $itemTransfer->getAmountSalesUnit()->getIdProductMeasurementSalesUnit()
        );
    }

    /**
     * @param float $price
     *
     * @return int
     */
    protected function roundPrice(float $price): int
    {
        return $this->utilPriceService->roundPrice($price);
    }
}
