<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Expander;

use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class ItemCurrencyExpander implements ItemCurrencyExpanderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithCurrencyIsoCode(array $itemTransfers): array
    {
        $currencyIsoCodesBySalesOrderIds = $this->salesRepository->getCurrencyIsoCodesBySalesOrderIds(
            $this->getSalesOrderIds($itemTransfers)
        );

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->setCurrencyIsoCode(
                $currencyIsoCodesBySalesOrderIds[$itemTransfer->getFkSalesOrder()] ?? null
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderIds(array $itemTransfers): array
    {
        $salesOrderIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getFkSalesOrder()) {
                $salesOrderIds[] = $itemTransfer->getFkSalesOrder();
            }
        }

        return array_unique($salesOrderIds);
    }
}
