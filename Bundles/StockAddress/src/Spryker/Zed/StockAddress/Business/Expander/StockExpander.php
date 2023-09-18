<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Expander;

use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface;

class StockExpander implements StockExpanderInterface
{
    /**
     * @var \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface
     */
    protected $stockAddressRepository;

    /**
     * @param \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface $stockAddressRepository
     */
    public function __construct(StockAddressRepositoryInterface $stockAddressRepository)
    {
        $this->stockAddressRepository = $stockAddressRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function expandStockTransfer(StockTransfer $stockTransfer): StockTransfer
    {
        if (!$stockTransfer->getIdStock()) {
            return $stockTransfer;
        }

        $stockAddressTransfers = $this->stockAddressRepository->getStockAddressesByStockIds([
            $stockTransfer->getIdStock(),
        ]);

        if (!$stockAddressTransfers) {
            return $stockTransfer;
        }

        $stockTransfer->setAddress($stockAddressTransfers[0]);

        return $stockTransfer;
    }
}
