<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\ItemSaver;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsOrderItemStateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpyOmsOrderItemStateEntityTransfer;
use Generated\Shared\Transfer\SpyOmsOrderProcessEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Spryker\Shared\Kernel\StrategyResolverInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface;
use Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;
use Spryker\Zed\Sales\SalesConfig;

class OrderItemsSaver implements OrderItemsSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface
     */
    protected $salesOrderSaverPluginExecutor;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface>
     */
    protected $orderItemsPostSavePlugins;

    /**
     * @var array<\Generated\Shared\Transfer\SpyOmsOrderProcessEntityTransfer>
     */
    protected $processEntityTransferCache = [];

    /**
     * @var array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemExpanderPluginInterface>
     */
    protected $orderItemExpanderPlugins;

    /**
     * @var \Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface
     */
    protected $orderStateMachineResolver;

    protected SalesConfig $salesConfig;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>>
     */
    protected StrategyResolverInterface $orderItemInitialStateProviderPluginStrategyResolver;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $entityManager
     * @param array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsPostSavePluginInterface> $orderItemsPostSavePlugins
     * @param \Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface $orderStateMachineResolver
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>> $orderItemInitialStateProviderPluginStrategyResolver
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfig
     */
    public function __construct(
        SalesToOmsInterface $omsFacade,
        SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor,
        SalesEntityManagerInterface $entityManager,
        array $orderItemsPostSavePlugins,
        OrderStateMachineResolverInterface $orderStateMachineResolver,
        StrategyResolverInterface $orderItemInitialStateProviderPluginStrategyResolver,
        SalesConfig $salesConfig
    ) {
        $this->omsFacade = $omsFacade;
        $this->salesOrderSaverPluginExecutor = $salesOrderSaverPluginExecutor;
        $this->entityManager = $entityManager;
        $this->orderItemsPostSavePlugins = $orderItemsPostSavePlugins;
        $this->orderStateMachineResolver = $orderStateMachineResolver;
        $this->orderItemInitialStateProviderPluginStrategyResolver = $orderItemInitialStateProviderPluginStrategyResolver;
        $this->salesConfig = $salesConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param bool|null $skipOrderItemsPostSavePlugins
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function saveOrderItems(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?bool $skipOrderItemsPostSavePlugins = false
    ): SaveOrderTransfer {
        return $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer, $skipOrderItemsPostSavePlugins) {
            return $this->saveOrderItemTransaction($quoteTransfer, $saveOrderTransfer, $skipOrderItemsPostSavePlugins);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param bool|null $skipOrderItemsPostSavePlugins
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function saveOrderItemTransaction(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer,
        ?bool $skipOrderItemsPostSavePlugins = false
    ): SaveOrderTransfer {
        $itemTransfers = $this->saveOrderItemsStrategy($quoteTransfer, $saveOrderTransfer);

        $quoteTransfer->setItems($itemTransfers);
        $saveOrderTransfer = $this->copyQuoteItemsToSaveOrderItems($saveOrderTransfer, $quoteTransfer);

        if ($skipOrderItemsPostSavePlugins) {
            return $saveOrderTransfer;
        }

        return $this->executeOrderItemsPostSavePlugins($saveOrderTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function saveOrderItemsStrategy(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): ArrayObject
    {
        // Optimized save logic depends on the presence of a unique identifier (e.g., OrderItemReference).
        // As this module does not expose such an identifier, a conditional check is introduced
        // to maintain backward compatibility and minimize installation complexity.
        if ($this->salesConfig->getItemHashColumn() !== '') {
            return $this->saveItemsBatch($quoteTransfer, $saveOrderTransfer);
        }

        return $this->saveItemsIndividually($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function saveItemsIndividually(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): ArrayObject
    {
        $itemTransfers = $quoteTransfer->getItems();
        $initialOmsOrderItemStateEntityTransfer = $this->getInitialStateEntityTransfer($quoteTransfer, $saveOrderTransfer);
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);

            $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
            $salesOrderItemEntityTransfer = $this->hydrateSalesOrderItemEntityTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderItemEntityTransfer, $itemTransfer, $initialOmsOrderItemStateEntityTransfer);
            $salesOrderItemEntityTransfer = $this->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);
            $salesOrderItemEntityTransfer = $this->entityManager->saveSalesOrderItems($salesOrderItemEntityTransfer);
            $itemTransfer->fromArray($salesOrderItemEntityTransfer->toArray(), true);

            if ($salesOrderItemEntityTransfer->getTaxRate()) {
                $itemTransfer->setTaxRate($salesOrderItemEntityTransfer->getTaxRateOrFail()->toFloat());
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function saveItemsBatch(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): ArrayObject
    {
        $itemTransfers = $quoteTransfer->getItems();

        $uniqueItemHashColumn = $this->salesConfig->getItemHashColumn();

        $initialOmsOrderItemStateEntityTransfer = $this->getInitialStateEntityTransfer($quoteTransfer, $saveOrderTransfer);
        $salesOrderItemEntityTransferList = [];
        $groupedItems = [];
        foreach ($itemTransfers as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);

            $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
            $salesOrderItemEntityTransfer = $this->hydrateSalesOrderItemEntityTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderItemEntityTransfer, $itemTransfer, $initialOmsOrderItemStateEntityTransfer);
            $salesOrderItemEntityTransfer = $this->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);
            $salesOrderItemEntityTransferList[] = $salesOrderItemEntityTransfer;
            $groupedItems[$salesOrderItemEntityTransfer->{'get' . $uniqueItemHashColumn . 'orFail'}()] = $itemTransfer;
        }

        $salesOrderItemEntityTransferList = $this->entityManager->saveSalesOrderItemsBatch($salesOrderItemEntityTransferList);
        foreach ($salesOrderItemEntityTransferList as $salesOrderItemEntityTransfer) {
            $itemTransfer = $groupedItems[$salesOrderItemEntityTransfer->{'get' . $uniqueItemHashColumn}()];
            $itemTransfer->fromArray($salesOrderItemEntityTransfer->toArray(), true);

            if ($salesOrderItemEntityTransfer->getTaxRate()) {
                $itemTransfer->setTaxRate($salesOrderItemEntityTransfer->getTaxRate()->toFloat());
            }
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function executeOrderItemsPostSavePlugins(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        foreach ($this->orderItemsPostSavePlugins as $orderItemsPostSavePlugin) {
            $saveOrderTransfer = $orderItemsPostSavePlugin->execute($saveOrderTransfer, $quoteTransfer);
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOmsOrderItemStateEntityTransfer|null
     */
    protected function getInitialStateEntityTransfer(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ?SpyOmsOrderItemStateEntityTransfer {
        $omsOrderItemStateTransfer = $this->executeOrderItemInitialStateProviderPlugins($quoteTransfer, $saveOrderTransfer);
        if (!$omsOrderItemStateTransfer && count($this->getOrderItemInitialStateProviderPlugins($quoteTransfer))) {
            return null;
        }

        // For BC reasons, if no plugin is registered, we use the default state
        if (!$omsOrderItemStateTransfer) {
            $omsOrderItemStateEntity = $this->omsFacade->getInitialStateEntity();
            $omsOrderItemStateTransfer = (new OmsOrderItemStateTransfer())
                ->fromArray($omsOrderItemStateEntity->toArray(), true);
        }

        $spyOmsOrderItemStateEntityTransfer = new SpyOmsOrderItemStateEntityTransfer();
        $spyOmsOrderItemStateEntityTransfer->fromArray($omsOrderItemStateTransfer->toArray(), true);

        return $spyOmsOrderItemStateEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function copyQuoteItemsToSaveOrderItems(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $quoteItemTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $quoteItemTransfers[] = clone $itemTransfer;
        }

        $saveOrderTransfer->setOrderItems(new ArrayObject($quoteItemTransfers));

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpyOmsOrderItemStateEntityTransfer|null $initialStateEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    protected function hydrateSalesOrderItemEntityTransfer(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer,
        ItemTransfer $itemTransfer,
        ?SpyOmsOrderItemStateEntityTransfer $initialStateEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        $processEntityTransfer = $this->getProcessEntityTransfer($quoteTransfer, $itemTransfer);
        $sanitizedItemTransfer = $this->sanitizeItemSumPrices(clone $itemTransfer);

        $salesOrderItemEntityTransfer->fromArray($itemTransfer->toArray(), true);
        $salesOrderItemEntityTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        $salesOrderItemEntityTransfer->setFkSalesShipment($sanitizedItemTransfer->getShipment()->getIdSalesShipment());
        $salesOrderItemEntityTransfer->setGrossPrice($sanitizedItemTransfer->getSumGrossPrice());
        $salesOrderItemEntityTransfer->setNetPrice($sanitizedItemTransfer->getSumNetPrice());
        $salesOrderItemEntityTransfer->setPrice($sanitizedItemTransfer->getSumPrice());
        $salesOrderItemEntityTransfer->setPriceToPayAggregation($sanitizedItemTransfer->getSumPriceToPayAggregation());
        $salesOrderItemEntityTransfer->setSubtotalAggregation($sanitizedItemTransfer->getSumSubtotalAggregation());
        $salesOrderItemEntityTransfer->setProductOptionPriceAggregation($sanitizedItemTransfer->getSumProductOptionPriceAggregation());
        $salesOrderItemEntityTransfer->setExpensePriceAggregation($sanitizedItemTransfer->getSumExpensePriceAggregation());
        $salesOrderItemEntityTransfer->setTaxAmount($sanitizedItemTransfer->getSumTaxAmount());
        $salesOrderItemEntityTransfer->setTaxAmountFullAggregation($sanitizedItemTransfer->getSumTaxAmountFullAggregation());
        $salesOrderItemEntityTransfer->setDiscountAmountAggregation($sanitizedItemTransfer->getSumDiscountAmountAggregation());
        $salesOrderItemEntityTransfer->setDiscountAmountFullAggregation($sanitizedItemTransfer->getSumDiscountAmountFullAggregation());
        $salesOrderItemEntityTransfer->setRefundableAmount($itemTransfer->getRefundableAmount());
        $salesOrderItemEntityTransfer->setProcess($processEntityTransfer);

        if ($initialStateEntityTransfer) {
            $salesOrderItemEntityTransfer->setFkOmsOrderItemState($initialStateEntityTransfer->getIdOmsOrderItemState());
            $salesOrderItemEntityTransfer->setState($initialStateEntityTransfer);
        }

        return $salesOrderItemEntityTransfer;
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function sanitizeItemSumPrices(ItemTransfer $itemTransfer): ItemTransfer
    {
        $itemTransfer->setSumGrossPrice($itemTransfer->getSumGrossPrice() ?? $itemTransfer->getUnitGrossPrice());
        $itemTransfer->setSumNetPrice($itemTransfer->getSumNetPrice() ?? $itemTransfer->getUnitNetPrice());
        $itemTransfer->setSumPrice($itemTransfer->getSumPrice() ?? $itemTransfer->getUnitPrice());
        $itemTransfer->setSumPriceToPayAggregation($itemTransfer->getSumPriceToPayAggregation() ?? $itemTransfer->getUnitPriceToPayAggregation());
        $itemTransfer->setSumSubtotalAggregation($itemTransfer->getSumSubtotalAggregation() ?? $itemTransfer->getUnitSubtotalAggregation());
        $itemTransfer->setSumProductOptionPriceAggregation($itemTransfer->getSumProductOptionPriceAggregation() ?? $itemTransfer->getUnitProductOptionPriceAggregation());
        $itemTransfer->setSumExpensePriceAggregation($itemTransfer->getSumExpensePriceAggregation() ?? $itemTransfer->getUnitExpensePriceAggregation());
        $itemTransfer->setSumTaxAmount($itemTransfer->getSumTaxAmount() ?? $itemTransfer->getUnitTaxAmount());
        $itemTransfer->setSumTaxAmountFullAggregation($itemTransfer->getSumTaxAmountFullAggregation() ?? $itemTransfer->getUnitTaxAmountFullAggregation());
        $itemTransfer->setSumDiscountAmountAggregation($itemTransfer->getSumDiscountAmountAggregation() ?? $itemTransfer->getUnitDiscountAmountAggregation());
        $itemTransfer->setSumDiscountAmountFullAggregation($itemTransfer->getSumDiscountAmountFullAggregation() ?? $itemTransfer->getUnitDiscountAmountFullAggregation());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOmsOrderProcessEntityTransfer
     */
    protected function getProcessEntityTransfer(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer): SpyOmsOrderProcessEntityTransfer
    {
        $processName = $this->orderStateMachineResolver->resolve($quoteTransfer, $itemTransfer);
        if (isset($this->processEntityTransferCache[$processName])) {
            return $this->processEntityTransferCache[$processName];
        }

        $processEntity = $this->omsFacade->getProcessEntity($processName);

        $processEntityTransfer = new SpyOmsOrderProcessEntityTransfer();
        $processEntityTransfer->fromArray($processEntity->toArray(), true);
        $this->processEntityTransferCache[$processName] = $processEntityTransfer;

        return $processEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer): void
    {
        $itemTransfer->requireUnitPrice()
            ->requireQuantity()
            ->requireName()
            ->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer
     */
    protected function executeOrderItemExpanderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItemEntityTransfer $salesOrderItemEntityTransfer
    ): SpySalesOrderItemEntityTransfer {
        return $this->salesOrderSaverPluginExecutor
            ->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OmsOrderItemStateTransfer|null
     */
    protected function executeOrderItemInitialStateProviderPlugins(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): ?OmsOrderItemStateTransfer {
        foreach ($this->getOrderItemInitialStateProviderPlugins($quoteTransfer) as $orderItemInitialStateProviderPlugin) {
            $omsOrderItemStateTransfer = $orderItemInitialStateProviderPlugin->getInitialItemState($quoteTransfer, $saveOrderTransfer);

            if ($omsOrderItemStateTransfer) {
                return $omsOrderItemStateTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemInitialStateProviderPluginInterface>
     */
    protected function getOrderItemInitialStateProviderPlugins(QuoteTransfer $quoteTransfer): array
    {
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();

        return $this->orderItemInitialStateProviderPluginStrategyResolver->get($quoteProcessFlowName);
    }
}
