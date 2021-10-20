<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrder\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOrderCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface;

class MerchantOrderCreator implements MerchantOrderCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface
     */
    protected $merchantSalesOrderEntityManager;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderItemCreatorInterface
     */
    protected $merchantOrderItemCreator;

    /**
     * @var \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface
     */
    protected $merchantOrderTotalsCreator;

    /**
     * @var array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface>
     */
    protected $merchantOrderPostCreatePlugins;

    /**
     * @param \Spryker\Zed\MerchantSalesOrder\Persistence\MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager
     * @param \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderItemCreatorInterface $merchantOrderItemCreator
     * @param \Spryker\Zed\MerchantSalesOrder\Business\Creator\MerchantOrderTotalsCreatorInterface $merchantOrderTotalsCreator
     * @param array<\Spryker\Zed\MerchantSalesOrderExtension\Dependency\Plugin\MerchantOrderPostCreatePluginInterface> $merchantOrderPostCreatePlugins
     */
    public function __construct(
        MerchantSalesOrderEntityManagerInterface $merchantSalesOrderEntityManager,
        MerchantOrderItemCreatorInterface $merchantOrderItemCreator,
        MerchantOrderTotalsCreatorInterface $merchantOrderTotalsCreator,
        array $merchantOrderPostCreatePlugins
    ) {
        $this->merchantSalesOrderEntityManager = $merchantSalesOrderEntityManager;
        $this->merchantOrderItemCreator = $merchantOrderItemCreator;
        $this->merchantOrderTotalsCreator = $merchantOrderTotalsCreator;
        $this->merchantOrderPostCreatePlugins = $merchantOrderPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderCollectionTransfer
     */
    public function createMerchantOrderCollection(OrderTransfer $orderTransfer): MerchantOrderCollectionTransfer
    {
        $orderTransfer->requireIdSalesOrder()
            ->requireOrderReference()
            ->requireItems();

        $merchantOrderCollectionTransfer = new MerchantOrderCollectionTransfer();
        $orderItemsGroupedByMerchantReference = $this->getOrderItemsGroupedByMerchantReference($orderTransfer);

        foreach ($orderItemsGroupedByMerchantReference as $merchantReference => $itemTransfers) {
            $merchantOrderCollectionTransfer->addMerchantOrder(
                $this->createMerchantOrderWithItemsAndTotals($orderTransfer, $merchantReference, $itemTransfers),
            );
        }

        $this->executeMerchantOrderPostCreatePlugins($merchantOrderCollectionTransfer);

        return $merchantOrderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer
     *
     * @return void
     */
    protected function executeMerchantOrderPostCreatePlugins(MerchantOrderCollectionTransfer $merchantOrderCollectionTransfer): void
    {
        foreach ($merchantOrderCollectionTransfer->getMerchantOrders() as $merchantOrderTransfer) {
            foreach ($this->merchantOrderPostCreatePlugins as $merchantOrderPostCreatePlugin) {
                $merchantOrderPostCreatePlugin->postCreate($merchantOrderTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer[]>
     */
    protected function getOrderItemsGroupedByMerchantReference(OrderTransfer $orderTransfer): array
    {
        $orderItemsGroupedByMerchantReference = [];

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getMerchantReference()) {
                continue;
            }
            /** @var string $merchantReference */
            $merchantReference = $itemTransfer->getMerchantReference();

            $orderItemsGroupedByMerchantReference[$merchantReference][] = $itemTransfer;
        }

        return $orderItemsGroupedByMerchantReference;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function createMerchantOrderWithItemsAndTotals(
        OrderTransfer $orderTransfer,
        string $merchantReference,
        array $itemTransfers
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $this->createMerchantOrder(
            $orderTransfer,
            $merchantReference,
        );
        $merchantOrderTransfer = $this->addMerchantOrderItemsToMerchantOrder(
            $merchantOrderTransfer,
            $itemTransfers,
        );
        $merchantOrderTransfer = $this->addExpensesToMerchantOrder(
            $merchantOrderTransfer,
            $orderTransfer->getExpenses(),
        );
        $merchantOrderTransfer->setPriceMode($orderTransfer->getPriceMode());

        return $merchantOrderTransfer->setTotals(
            $this->merchantOrderTotalsCreator->createMerchantOrderTotals($merchantOrderTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function addMerchantOrderItemsToMerchantOrder(
        MerchantOrderTransfer $merchantOrderTransfer,
        array $itemTransfers
    ): MerchantOrderTransfer {
        foreach ($itemTransfers as $itemTransfer) {
            $merchantOrderTransfer->addMerchantOrderItem(
                $this->merchantOrderItemCreator->createMerchantOrderItem($itemTransfer, $merchantOrderTransfer),
            );
        }

        return $merchantOrderTransfer;
    }

    /**
     * @phpstan-param \ArrayObject<array-key, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    protected function addExpensesToMerchantOrder(
        MerchantOrderTransfer $merchantOrderTransfer,
        ArrayObject $expenseTransfers
    ): MerchantOrderTransfer {
        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getMerchantReference() !== $merchantOrderTransfer->getMerchantReference()) {
                continue;
            }

            $merchantOrderTransfer->addExpense($expenseTransfer);
        }

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrder(OrderTransfer $orderTransfer, string $merchantReference): MerchantOrderTransfer
    {
        /** @var string $orderReference */
        $orderReference = $orderTransfer->getOrderReference();

        $merchantOrderTransfer = (new MerchantOrderTransfer())
            ->setMerchantReference($merchantReference)
            ->setIdOrder($orderTransfer->getIdSalesOrder())
            ->setOrder($orderTransfer)
            ->setMerchantOrderReference(
                sprintf('%s--%s', $orderReference, $merchantReference),
            );

        return $this->merchantSalesOrderEntityManager->createMerchantOrder($merchantOrderTransfer);
    }
}
