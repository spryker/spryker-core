<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityCartConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\AvailabilityCartConnector\Business\AvailabilityCartConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class AvailabilityCartConnectorBusinessTester extends Actor
{
    use _generated\AvailabilityCartConnectorBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param int $availableQuantity
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductWithAvailabilityForStore(
        StoreTransfer $storeTransfer,
        int $availableQuantity
    ): ProductConcreteTransfer {
        $productConcreteTransfer = $this->haveFullProduct();

        $this->haveProductInStockForStore(
            $storeTransfer,
            [
                StockProductTransfer::SKU => $productConcreteTransfer->getSkuOrFail(),
                StockProductTransfer::IS_NEVER_OUT_OF_STOCK => false,
                StockProductTransfer::QUANTITY => $availableQuantity,
            ],
        );
        $this->haveAvailabilityConcrete($productConcreteTransfer->getSkuOrFail(), $storeTransfer, $availableQuantity);

        return $productConcreteTransfer;
    }
}
