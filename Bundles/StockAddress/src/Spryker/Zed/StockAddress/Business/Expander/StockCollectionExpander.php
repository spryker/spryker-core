<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Expander;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface;

class StockCollectionExpander implements StockCollectionExpanderInterface
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
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function expandStockCollection(StockCollectionTransfer $stockCollectionTransfer): StockCollectionTransfer
    {
        $stockIds = $this->extractStockIdsFromStockCollectionTransfer($stockCollectionTransfer);
        $stockAddressTransfers = $this->stockAddressRepository->getStockAddressesByStockIds($stockIds);
        if ($stockAddressTransfers === []) {
            return $stockCollectionTransfer;
        }

        $indexedStockAddressTransfers = $this->getStockAddressTransfersIndexedByIdStock($stockAddressTransfers);
        foreach ($stockCollectionTransfer->getStocks() as $stockTransfer) {
            if (!isset($indexedStockAddressTransfers[$stockTransfer->getIdStockOrFail()])) {
                continue;
            }

            $stockTransfer->setAddress($indexedStockAddressTransfers[$stockTransfer->getIdStockOrFail()]);
        }

        return $stockCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return int[]
     */
    protected function extractStockIdsFromStockCollectionTransfer(StockCollectionTransfer $stockCollectionTransfer): array
    {
        return array_map(function (StockTransfer $stockTransfer) {
            return $stockTransfer->getIdStockOrFail();
        }, $stockCollectionTransfer->getStocks()->getArrayCopy());
    }

    /**
     * @param \Generated\Shared\Transfer\StockAddressTransfer[] $stockAddressTransfers
     *
     * @return \Generated\Shared\Transfer\StockAddressTransfer[]
     */
    protected function getStockAddressTransfersIndexedByIdStock(array $stockAddressTransfers): array
    {
        $indexedStockAddressTransfers = [];
        foreach ($stockAddressTransfers as $stockAddressTransfer) {
            $indexedStockAddressTransfers[$stockAddressTransfer->getIdStockOrFail()] = $stockAddressTransfer;
        }

        return $indexedStockAddressTransfers;
    }
}
