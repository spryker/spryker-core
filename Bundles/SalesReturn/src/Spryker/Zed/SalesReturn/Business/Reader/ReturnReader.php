<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnItemFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface;

class ReturnReader implements ReturnReaderInterface
{
    protected const GLOSSARY_KEY_RETURN_NOT_EXISTS = 'return.validation.error.not_exists';

    protected const ID_SALES_RETURN_SORT_FIELD = 'id_sales_return';
    protected const DEFAULT_SORT_DIRECTION = 'DESC';
    protected const DEFAULT_OFFSET = 1;
    protected const DEFAULT_LIMIT = 10;

    /**
     * @var \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface
     */
    protected $salesReturnRepository;

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface
     */
    protected $returnTotalCalculator;

    /**
     * @var \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnExpanderPluginInterface[]
     */
    protected $returnExpanderPlugins;

    /**
     * @deprecated Will be removed without replacement.
     *
     * @var \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnCollectionExpanderPluginInterface[]
     */
    protected $returnCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesReturn\Persistence\SalesReturnRepositoryInterface $salesReturnRepository
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReturn\Business\Calculator\ReturnTotalCalculatorInterface $returnTotalCalculator
     * @param \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnExpanderPluginInterface[] $returnExpanderPlugins
     * @param \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnCollectionExpanderPluginInterface[] $returnCollectionExpanderPlugins
     */
    public function __construct(
        SalesReturnRepositoryInterface $salesReturnRepository,
        SalesReturnToSalesFacadeInterface $salesFacade,
        ReturnTotalCalculatorInterface $returnTotalCalculator,
        array $returnExpanderPlugins,
        array $returnCollectionExpanderPlugins
    ) {
        $this->salesReturnRepository = $salesReturnRepository;
        $this->salesFacade = $salesFacade;
        $this->returnTotalCalculator = $returnTotalCalculator;
        $this->returnExpanderPlugins = $returnExpanderPlugins;
        $this->returnCollectionExpanderPlugins = $returnCollectionExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function getReturn(ReturnFilterTransfer $returnFilterTransfer): ReturnResponseTransfer
    {
        $returnFilterTransfer->requireReturnReference();

        $returnTransfer = $this
            ->getReturnCollection($returnFilterTransfer)
            ->getReturns()
            ->getIterator()
            ->current();

        if (!$returnTransfer) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_RETURN_NOT_EXISTS);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true)
            ->setReturn($returnTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturnCollection(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        $returnFilterTransfer = $this->setDefaultFilter($returnFilterTransfer);

        $returnCollectionTransfer = $this->salesReturnRepository->getReturnCollectionByFilter($returnFilterTransfer);

        $returnCollectionTransfer = $this->expandReturnCollectionWithReturnItems($returnCollectionTransfer);
        $returnCollectionTransfer = $this->expandReturnCollectionWithReturnTotals($returnCollectionTransfer);
        $returnCollectionTransfer = $this->executeReturnExpanderPlugins($returnCollectionTransfer);

        foreach ($this->returnCollectionExpanderPlugins as $collectionExpanderPlugin) {
            $returnCollectionTransfer = $collectionExpanderPlugin->expand($returnCollectionTransfer);
        }

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getOrderItems(OrderItemFilterTransfer $orderItemFilterTransfer): ArrayObject
    {
        return $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    protected function expandReturnCollectionWithReturnItems(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        $returnIds = $this->extractReturnIds($returnCollectionTransfer);
        $returnItemFilterTransfer = (new ReturnItemFilterTransfer())->setReturnIds($returnIds);

        $returnItemTransfers = $this->salesReturnRepository->getReturnItemsByFilter($returnItemFilterTransfer);
        $mappedReturnItemTransfers = $this->mapReturnItemsByIdReturn($returnItemTransfers);

        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            $returnTransfer->setReturnItems(
                new ArrayObject($mappedReturnItemTransfers[$returnTransfer->getIdSalesReturn()] ?? [])
            );

            $this->expandReturnWithOrderItems($returnTransfer);
        }

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return int[]
     */
    protected function extractReturnIds(ReturnCollectionTransfer $returnCollectionTransfer): array
    {
        $returnIds = [];

        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            $returnIds[] = $returnTransfer->getIdSalesReturn();
        }

        return $returnIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    protected function expandReturnWithOrderItems(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $salesOrderItemIds = $this->extractSalesOrderItemIds($returnTransfer);
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds);

        $itemTransfers = $this->getOrderItems($orderItemFilterTransfer);
        $mappedItemTransfers = $this->mapOrderItemsByIdSalesOrderItem($itemTransfers);

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $returnItemTransfer->setOrderItem(
                $mappedItemTransfers[$returnItemTransfer->getOrderItem()->getIdSalesOrderItem()] ?? null
            );
        }

        return $returnTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int[]
     */
    protected function extractSalesOrderItemIds(ReturnTransfer $returnTransfer): array
    {
        $salesOrderItemIds = [];

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $salesOrderItemIds[] = $returnItemTransfer->getOrderItem()->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapOrderItemsByIdSalesOrderItem(ArrayObject $itemTransfers): array
    {
        $mappedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $mappedItemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        return $mappedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer[] $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnItemTransfer[][]
     */
    protected function mapReturnItemsByIdReturn(array $returnItemTransfers): array
    {
        $mappedReturnItemTransfers = [];

        foreach ($returnItemTransfers as $returnItemTransfer) {
            $mappedReturnItemTransfers[$returnItemTransfer->getIdSalesReturn()][] = $returnItemTransfer;
        }

        return $mappedReturnItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    protected function expandReturnCollectionWithReturnTotals(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            $returnTransfer->setReturnTotals(
                $this->returnTotalCalculator->calculateReturnTotals($returnTransfer)
            );
        }

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    protected function executeReturnExpanderPlugins(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            foreach ($this->returnExpanderPlugins as $plugin) {
                $returnTransfer = $plugin->expand($returnTransfer);
            }
        }

        return $returnCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnFilterTransfer
     */
    protected function setDefaultFilter(ReturnFilterTransfer $returnFilterTransfer): ReturnFilterTransfer
    {
        $filterTransfer = $returnFilterTransfer->getFilter() ?? new FilterTransfer();

        $defaultFilterTransfer = (new FilterTransfer())
            ->setOffset(static::DEFAULT_OFFSET)
            ->setLimit(static::DEFAULT_LIMIT)
            ->setOrderBy(static::ID_SALES_RETURN_SORT_FIELD)
            ->setOrderDirection(static::DEFAULT_SORT_DIRECTION);

        $defaultFilterTransfer->fromArray($filterTransfer->modifiedToArray());

        $returnFilterTransfer->setFilter($defaultFilterTransfer);

        return $returnFilterTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function createErrorResponse(string $message): ReturnResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
