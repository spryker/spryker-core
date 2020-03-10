<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderItemReader implements OrderItemReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @var \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[]
     */
    protected $orderItemExpanderPlugins;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     * @param \Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface[] $orderItemExpanderPlugins
     */
    public function __construct(
        SalesRepositoryInterface $salesRepository,
        array $orderItemExpanderPlugins
    ) {
        $this->salesRepository = $salesRepository;
        $this->orderItemExpanderPlugins = $orderItemExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ItemCollectionTransfer
    {
        $itemTransfers = $this->salesRepository->getOrderItems($orderItemFilterTransfer);

        $itemTransfers = $this->deriveOrderItemsUnitPrices($itemTransfers);
        $itemTransfers = $this->executeOrderItemExpanderPlugins($itemTransfers);

        return (new ItemCollectionTransfer())
            ->setItems(new ArrayObject($itemTransfers));
    }

    /**
     * Unit prices are populated for presentation purposes only. For further calculations use sum prices or properly populated unit prices.
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function deriveOrderItemsUnitPrices(array $itemTransfers): array
    {
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer
                ->setUnitGrossPrice((int)round($itemTransfer->getSumGrossPrice() / $itemTransfer->getQuantity()))
                ->setUnitNetPrice((int)round($itemTransfer->getSumNetPrice() / $itemTransfer->getQuantity()))
                ->setUnitPrice((int)round($itemTransfer->getSumPrice() / $itemTransfer->getQuantity()))
                ->setUnitSubtotalAggregation((int)round($itemTransfer->getSumSubtotalAggregation() / $itemTransfer->getQuantity()))
                ->setUnitDiscountAmountAggregation((int)round($itemTransfer->getSumDiscountAmountAggregation() / $itemTransfer->getQuantity()))
                ->setUnitDiscountAmountFullAggregation((int)round($itemTransfer->getSumDiscountAmountFullAggregation() / $itemTransfer->getQuantity()))
                ->setUnitExpensePriceAggregation((int)round($itemTransfer->getSumExpensePriceAggregation() / $itemTransfer->getQuantity()))
                ->setUnitTaxAmount((int)round($itemTransfer->getSumTaxAmount() / $itemTransfer->getQuantity()))
                ->setUnitTaxAmountFullAggregation((int)round($itemTransfer->getSumTaxAmountFullAggregation() / $itemTransfer->getQuantity()))
                ->setUnitPriceToPayAggregation((int)round($itemTransfer->getSumPriceToPayAggregation() / $itemTransfer->getQuantity()));
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeOrderItemExpanderPlugins(array $itemTransfers): array
    {
        foreach ($this->orderItemExpanderPlugins as $orderItemExpanderPlugin) {
            $itemTransfers = $orderItemExpanderPlugin->expand($itemTransfers);
        }

        return $itemTransfers;
    }
}
