<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Spryker\Shared\Kernel\StrategyResolverInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface;
use Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\SalesConfig;

/**
 * @deprecated Use {@link \Spryker\Zed\Sales\Business\OrderWriter\SalesOrderWriter} instead.
 */
class SalesOrderSaver implements SalesOrderSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $salesConfiguration;

    /**
     * @var \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    protected $localePropelQuery;

    /**
     * @var array<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface>
     */
    protected $orderExpanderPreSavePlugins;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface
     */
    protected $salesOrderSaverPluginExecutor;

    /**
     * @var \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface
     */
    protected $salesOrderItemMapper;

    /**
     * @var \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>>
     */
    protected $orderPostSavePluginStrategyResolver;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface
     */
    protected $orderStateMachineResolver;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     * @param \Orm\Zed\Locale\Persistence\SpyLocaleQuery $localePropelQuery
     * @param array<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface> $orderExpanderPreSavePlugins
     * @param \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor
     * @param \Spryker\Zed\Sales\Persistence\Propel\Mapper\SalesOrderItemMapperInterface $salesOrderItemMapper
     * @param \Spryker\Shared\Kernel\StrategyResolverInterface<list<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>> $orderPostSavePluginStrategyResolver
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface $storeFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Sales\Business\StateMachineResolver\OrderStateMachineResolverInterface $orderStateMachineResolver
     */
    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration,
        SpyLocaleQuery $localePropelQuery,
        $orderExpanderPreSavePlugins,
        SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor,
        SalesOrderItemMapperInterface $salesOrderItemMapper,
        StrategyResolverInterface $orderPostSavePluginStrategyResolver,
        SalesToStoreInterface $storeFacade,
        SalesToLocaleInterface $localeFacade,
        OrderStateMachineResolverInterface $orderStateMachineResolver
    ) {
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
        $this->salesConfiguration = $salesConfiguration;
        $this->localePropelQuery = $localePropelQuery;
        $this->orderExpanderPreSavePlugins = $orderExpanderPreSavePlugins;
        $this->salesOrderSaverPluginExecutor = $salesOrderSaverPluginExecutor;
        $this->salesOrderItemMapper = $salesOrderItemMapper;
        $this->orderPostSavePluginStrategyResolver = $orderPostSavePluginStrategyResolver;
        $this->storeFacade = $storeFacade;
        $this->localeFacade = $localeFacade;
        $this->orderStateMachineResolver = $orderStateMachineResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderSales(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->assertOrderRequirements($quoteTransfer);

        $orderReference = $this->orderReferenceGenerator->generateOrderReference($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer, $orderReference) {
            $this->saveOrderSalesTransaction($quoteTransfer, $saveOrderTransfer, $orderReference);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param string $orderReference
     *
     * @return void
     */
    protected function saveOrderSalesTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer, string $orderReference): void
    {
        $salesOrderEntity = $this->saveOrderEntity($quoteTransfer, $orderReference);

        $this->saveOrderTotals($quoteTransfer, $salesOrderEntity->getIdSalesOrder());
        $this->saveOrderItems($quoteTransfer, $salesOrderEntity);

        $this->hydrateSaveOrderTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderEntity);

        $this->executeOrderPostSavePlugins($saveOrderTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function executeOrderPostSavePlugins(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        $quoteProcessFlowName = $quoteTransfer->getQuoteProcessFlow()?->getNameOrFail();
        $orderPostSavePlugins = $this->orderPostSavePluginStrategyResolver->get($quoteProcessFlowName);

        foreach ($orderPostSavePlugins as $orderPostSavePlugin) {
            $saveOrderTransfer = $orderPostSavePlugin->execute($saveOrderTransfer, $quoteTransfer);
        }

        return $saveOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idSalesOrder
     *
     * @return void
     */
    protected function saveOrderTotals(QuoteTransfer $quoteTransfer, $idSalesOrder)
    {
        $taxTotal = 0;
        if ($quoteTransfer->getTotals()->getTaxTotal()) {
            $taxTotal = $quoteTransfer->getTotals()->getTaxTotal()->getAmount();
        }

        $salesOrderTotalsEntity = new SpySalesOrderTotals();
        $salesOrderTotalsEntity->setFkSalesOrder($idSalesOrder);
        $salesOrderTotalsEntity->fromArray($quoteTransfer->getTotals()->toArray());
        $salesOrderTotalsEntity->setTaxTotal($taxTotal);
        $salesOrderTotalsEntity->setOrderExpenseTotal($quoteTransfer->getTotals()->getExpenseTotal());
        $salesOrderTotalsEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer, string $orderReference)
    {
        $salesOrderEntity = $this->createSalesOrderEntity();
        $this->hydrateSalesOrderEntity($quoteTransfer, $salesOrderEntity, $orderReference);
        $this->hydrateAddresses($quoteTransfer, $salesOrderEntity);
        $this->addLocale($salesOrderEntity);
        $salesOrderEntity->save();

        return $salesOrderEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $billingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $salesOrderEntity->setBillingAddress($billingAddressEntity);

        if ($quoteTransfer->getShippingAddress() !== null && $quoteTransfer->getShippingAddress()->getFirstName() !== null) {
            $shippingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getShippingAddress());
            $salesOrderEntity->setShippingAddress($shippingAddressEntity);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function addLocale(SpySalesOrder $salesOrderEntity)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $localeEntity = $this->localePropelQuery
            ->filterByLocaleName($localeTransfer->getLocaleNameOrFail())
            ->findOne();

        if ($localeEntity) {
            $salesOrderEntity->setLocale($localeEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function saveSalesOrderAddress(AddressTransfer $addressTransfer)
    {
        $salesOrderAddressEntity = $this->createSalesOrderAddressEntity();
        $this->hydrateSalesOrderAddress($addressTransfer, $salesOrderAddressEntity);
        $salesOrderAddressEntity->save();

        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntity->getIdSalesOrderAddress());

        return $salesOrderAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $salesOrderAddressEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderAddress(AddressTransfer $addressTransfer, SpySalesOrderAddress $salesOrderAddressEntity)
    {
        $salesOrderAddressEntity->fromArray($addressTransfer->toArray());
        $salesOrderAddressEntity->setFkCountry(
            $this->countryFacade->getCountryByIso2Code($addressTransfer->getIso2Code())->getIdCountryOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param string $orderReference
     *
     * @return void
     */
    protected function hydrateSalesOrderEntity(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity, string $orderReference)
    {
        $salesOrderEntity->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $this->hydrateSalesOrderCustomer($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->setPriceMode($quoteTransfer->getPriceMode());
        $salesOrderEntity->setStore($quoteTransfer->getStore() ? $quoteTransfer->getStore()->getName() : $this->storeFacade->getCurrentStore()->getNameOrFail());
        $salesOrderEntity->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
        $salesOrderEntity->setOrderReference($orderReference);
        $salesOrderEntity->setIsTest($this->salesConfiguration->isTestOrder($quoteTransfer));

        $this->hydrateSalesOrderEntityFromPlugins($quoteTransfer, $salesOrderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderEntityFromPlugins(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity): void
    {
        $salesOrderEntityTransfer = new SpySalesOrderEntityTransfer();
        $salesOrderEntityTransfer->fromArray((array)$salesOrderEntity->toArray(), true);

        foreach ($this->orderExpanderPreSavePlugins as $preSaveHydrateOrderPlugin) {
            $salesOrderEntityTransfer = $preSaveHydrateOrderPlugin->expand($salesOrderEntityTransfer, $quoteTransfer);
        }

        $salesOrderEntity->fromArray($salesOrderEntityTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderCustomer(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        $customerData = $customerTransfer->modifiedToArray();

        if (isset($customerData['created_at'])) {
            unset($customerData['created_at']);
        }

        if (isset($customerData['updated_at'])) {
            unset($customerData['updated_at']);
        }

        $salesOrderEntity->fromArray($customerData);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function saveOrderItems(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertItemRequirements($itemTransfer);

            $salesOrderItemEntity = $this->createSalesOrderItemEntity();
            $this->hydrateSalesOrderItemEntity($salesOrderEntity, $quoteTransfer, $salesOrderItemEntity, $itemTransfer);
            $salesOrderItemEntity = $this->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntity);

            $salesOrderItemEntity->save();
            $itemTransfer->fromArray($salesOrderItemEntity->toArray(), true);
        }
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function hydrateSalesOrderItemEntity(
        SpySalesOrder $salesOrderEntity,
        QuoteTransfer $quoteTransfer,
        SpySalesOrderItem $salesOrderItemEntity,
        ItemTransfer $itemTransfer
    ) {
        $processEntity = $this->getProcessEntity($quoteTransfer, $itemTransfer);
        $initialStateEntity = $this->omsFacade->getInitialStateEntity();

        $sanitizedItemTransfer = $this->sanitizeItemSumPrices(clone $itemTransfer);

        $salesOrderItemEntity->fromArray($itemTransfer->toArray());
        $salesOrderItemEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItemEntity->setFkOmsOrderItemState($initialStateEntity->getIdOmsOrderItemState());
        $salesOrderItemEntity->setGrossPrice($sanitizedItemTransfer->getSumGrossPrice());
        $salesOrderItemEntity->setNetPrice($sanitizedItemTransfer->getSumNetPrice());
        $salesOrderItemEntity->setPrice($sanitizedItemTransfer->getSumPrice());
        $salesOrderItemEntity->setPriceToPayAggregation($sanitizedItemTransfer->getSumPriceToPayAggregation());
        $salesOrderItemEntity->setSubtotalAggregation($sanitizedItemTransfer->getSumSubtotalAggregation());
        $salesOrderItemEntity->setProductOptionPriceAggregation($sanitizedItemTransfer->getSumProductOptionPriceAggregation());
        $salesOrderItemEntity->setExpensePriceAggregation($sanitizedItemTransfer->getSumExpensePriceAggregation());
        $salesOrderItemEntity->setTaxAmount($sanitizedItemTransfer->getSumTaxAmount());
        $salesOrderItemEntity->setTaxAmountFullAggregation($sanitizedItemTransfer->getSumTaxAmountFullAggregation());
        $salesOrderItemEntity->setDiscountAmountAggregation($sanitizedItemTransfer->getSumDiscountAmountAggregation());
        $salesOrderItemEntity->setDiscountAmountFullAggregation($sanitizedItemTransfer->getSumDiscountAmountFullAggregation());
        $salesOrderItemEntity->setRefundableAmount($itemTransfer->getRefundableAmount());
        $salesOrderItemEntity->setProcess($processEntity);
    }

    /**
     * @deprecated For BC reasons the missing sum prices are mirrored from unit prices
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function sanitizeItemSumPrices(ItemTransfer $itemTransfer)
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
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $processName = $this->orderStateMachineResolver->resolve($quoteTransfer, $itemTransfer);
        $processEntity = $this->omsFacade->getProcessEntity($processName);

        return $processEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $saveOrderTransfer = $this->getSaveOrderTransfer($saveOrderTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem(clone $itemTransfer);
        }

        $saveOrderTransfer->fromArray((array)$salesOrderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer)
    {
        return $saveOrderTransfer;
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function createSalesOrderEntity()
    {
        return new SpySalesOrder();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemEntity()
    {
        return new SpySalesOrderItem();
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createOrderTransfer()
    {
        return new OrderTransfer();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderAddress
     */
    protected function createSalesOrderAddressEntity()
    {
        return new SpySalesOrderAddress();
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function createSaveOrderTransfer()
    {
        return new SaveOrderTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireUnitPrice()
            ->requireQuantity()
            ->requireName()
            ->requireSku();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertOrderRequirements(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer
            ->requireItems()
            ->requireTotals();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $spySalesOrderItemEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function executeOrderItemExpanderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        SpySalesOrderItem $spySalesOrderItemEntity
    ): SpySalesOrderItem {
        $salesOrderItemEntity = $this->salesOrderItemMapper
            ->mapSpySalesOrderItemEntityToSalesOrderItemEntity($spySalesOrderItemEntity, new SpySalesOrderItemEntityTransfer());
        $salesOrderItemEntity = $this->salesOrderSaverPluginExecutor
            ->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntity);
        $spySalesOrderItemEntity = $this->salesOrderItemMapper
            ->mapSalesOrderItemEntityToSpySalesOrderItemEntity($salesOrderItemEntity, new SpySalesOrderItem());

        return $spySalesOrderItemEntity;
    }
}
