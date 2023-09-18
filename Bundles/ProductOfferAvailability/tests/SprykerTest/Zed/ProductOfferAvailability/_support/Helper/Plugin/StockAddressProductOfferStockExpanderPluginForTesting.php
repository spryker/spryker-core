<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailability\Helper\Plugin;

use Generated\Shared\Transfer\StockAddressTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface;

class StockAddressProductOfferStockExpanderPluginForTesting implements StockTransferProductOfferStockExpanderPluginInterface
{
    /**
     * @var int
     */
    protected $stockId;

    /**
     * @var \Generated\Shared\Transfer\StockAddressTransfer
     */
    protected $stockAddressToHydrate;

    /**
     * @param int $stockId
     * @param \Generated\Shared\Transfer\StockAddressTransfer $stockAddressToHydrate
     */
    public function __construct(
        int $stockId,
        StockAddressTransfer $stockAddressToHydrate
    ) {
        $this->stockId = $stockId;
        $this->stockAddressToHydrate = $stockAddressToHydrate;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function expand(StockTransfer $stockTransfer): StockTransfer
    {
        if ($stockTransfer->getIdStock() === $this->stockId) {
            $stockTransfer->setAddress($this->stockAddressToHydrate);
        }

        return $stockTransfer;
    }
}
