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
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderTotals;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface;
use Spryker\Zed\Sales\SalesConfig;

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
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface[]
     */
    protected $orderExpanderPreSavePlugins;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface
     */
    protected $salesOrderSaverPluginExecutor;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface
     */
    protected $salesOrderItemMapper;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToOmsInterface $omsFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface[] $orderExpanderPreSavePlugins
     * @param \Spryker\Zed\Sales\Business\Model\Order\SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor
     * @param \Spryker\Zed\Sales\Business\Model\OrderItem\SalesOrderItemMapperInterface $salesOrderItemMapper
     */
    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToOmsInterface $omsFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration,
        LocaleQueryContainerInterface $localeQueryContainer,
        Store $store,
        $orderExpanderPreSavePlugins,
        SalesOrderSaverPluginExecutorInterface $salesOrderSaverPluginExecutor,
        SalesOrderItemMapperInterface $salesOrderItemMapper
    ) {
        $this->countryFacade = $countryFacade;
        $this->omsFacade = $omsFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
        $this->salesConfiguration = $salesConfiguration;
        $this->localeQueryContainer = $localeQueryContainer;
        $this->store = $store;
        $this->orderExpanderPreSavePlugins = $orderExpanderPreSavePlugins;
        $this->salesOrderSaverPluginExecutor = $salesOrderSaverPluginExecutor;
        $this->salesOrderItemMapper = $salesOrderItemMapper;
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

        $salesOrderEntity = $this->handleDatabaseTransaction(function () use ($quoteTransfer) {
            return $this->saveOrderSalesTransaction($quoteTransfer);
        });

        $this->hydrateSaveOrderTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderSalesTransaction(QuoteTransfer $quoteTransfer)
    {
        $salesOrderEntity = $this->saveOrderEntity($quoteTransfer);

        $this->saveOrderTotals($quoteTransfer, $salesOrderEntity->getIdSalesOrder());
        $this->saveOrderItems($quoteTransfer, $salesOrderEntity);

        return $salesOrderEntity;
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
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer)
    {
        $salesOrderEntity = $this->createSalesOrderEntity();
        $this->hydrateSalesOrderEntity($quoteTransfer, $salesOrderEntity);
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
        $shippingAddressEntity = $this->saveSalesOrderAddress($quoteTransfer->getShippingAddress());

        $salesOrderEntity->setBillingAddress($billingAddressEntity);
        $salesOrderEntity->setShippingAddress($shippingAddressEntity);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function addLocale(SpySalesOrder $salesOrderEntity)
    {
        $localeName = $this->store->getCurrentLocale();
        $localeEntity = $this->localeQueryContainer->queryLocaleByName($localeName)->findOne();

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
            $this->countryFacade->getIdCountryByIso2Code($addressTransfer->getIso2Code())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return void
     */
    protected function hydrateSalesOrderEntity(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity)
    {
        $salesOrderEntity->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $this->hydrateSalesOrderCustomer($quoteTransfer, $salesOrderEntity);
        $salesOrderEntity->setPriceMode($quoteTransfer->getPriceMode());
        $salesOrderEntity->setStore($this->store->getStoreName());
        $salesOrderEntity->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
        $salesOrderEntity->setOrderReference($this->orderReferenceGenerator->generateOrderReference($quoteTransfer));
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
        $salesOrderEntityTransfer->fromArray($salesOrderEntity->toArray(), true);

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
            $itemTransfer->setIdSalesOrderItem($salesOrderItemEntity->getIdSalesOrderItem());
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

        $salesOrderItemEntity->fromArray($itemTransfer->toArray());
        $salesOrderItemEntity->setFkSalesOrder($salesOrderEntity->getIdSalesOrder());
        $salesOrderItemEntity->setFkOmsOrderItemState($initialStateEntity->getIdOmsOrderItemState());
        $salesOrderItemEntity->setGrossPrice($itemTransfer->getSumGrossPrice());
        $salesOrderItemEntity->setNetPrice($itemTransfer->getSumNetPrice());

        $salesOrderItemEntity->setPrice($itemTransfer->getSumPrice());
        $salesOrderItemEntity->setPriceToPayAggregation($itemTransfer->getSumPriceToPayAggregation());
        $salesOrderItemEntity->setSubtotalAggregation($itemTransfer->getSumSubtotalAggregation());
        $salesOrderItemEntity->setProductOptionPriceAggregation($itemTransfer->getSumProductOptionPriceAggregation());
        $salesOrderItemEntity->setExpensePriceAggregation($itemTransfer->getSumExpensePriceAggregation());
        $salesOrderItemEntity->setTaxAmount($itemTransfer->getSumTaxAmount());
        $salesOrderItemEntity->setTaxAmountFullAggregation($itemTransfer->getSumTaxAmountFullAggregation());
        $salesOrderItemEntity->setDiscountAmountAggregation($itemTransfer->getSumDiscountAmountAggregation());
        $salesOrderItemEntity->setDiscountAmountFullAggregation($itemTransfer->getSumDiscountAmountFullAggregation());
        $salesOrderItemEntity->setRefundableAmount($itemTransfer->getRefundableAmount());
        $salesOrderItemEntity->setProcess($processEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function getProcessEntity(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $processName = $this->salesConfiguration->determineProcessForOrderItem($quoteTransfer, $itemTransfer);
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

        $saveOrderTransfer->fromArray($salesOrderEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function getSaveOrderTransfer(SaveOrderTransfer $saveOrderTransfer)
    {
        if ($saveOrderTransfer === null) {
            $saveOrderTransfer = $this->createSaveOrderTransfer();
        }

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
    protected function executeOrderItemExpanderPreSavePlugins(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, SpySalesOrderItem $spySalesOrderItemEntity): SpySalesOrderItem
    {
        $salesOrderItemEntity = $this->salesOrderItemMapper->mapSpySalesOrderItemEntityToSalesOrderItemEntity($spySalesOrderItemEntity);
        $salesOrderItemEntity = $this->salesOrderSaverPluginExecutor->executeOrderItemExpanderPreSavePlugins($quoteTransfer, $itemTransfer, $salesOrderItemEntity);
        $spySalesOrderItemEntity = $this->salesOrderItemMapper->mapSalesOrderItemEntityToSpySalesOrderItemEntity($salesOrderItemEntity);

        return $spySalesOrderItemEntity;
    }
}
