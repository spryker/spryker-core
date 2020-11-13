<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Availability;

use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AvailabilityPersistenceTester extends Actor
{
    use _generated\AvailabilityPersistenceTesterActions;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StockTransfer[] $stockTransfers
     * @param int $productQuantity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductWithStockAndAvailability(
        StoreTransfer $storeTransfer,
        LocaleTransfer $localeTransfer,
        array $stockTransfers,
        int $productQuantity
    ): ProductConcreteTransfer {
        $localizedAttributes = (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::LOCALE => $localeTransfer,
        ]))->build()->toArray();

        $productConcreteTransfer = $this->haveProduct(
            [ProductConcreteTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributes]],
            [ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributes]]
        );

        foreach ($stockTransfers as $stockTransfer) {
            $this->haveStockProduct([
                StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
                StockProductTransfer::QUANTITY => $productQuantity,
                StockProductTransfer::STOCK_TYPE => $stockTransfer->getName(),
            ]);
        }

        $this->haveAvailabilityConcrete($productConcreteTransfer->getSku(), $storeTransfer, new Decimal(20));

        return $productConcreteTransfer;
    }
}
