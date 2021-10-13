<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\OrderWriter;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;
use Spryker\Zed\Sales\SalesConfig;

class SalesOrderWriter implements SalesOrderWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface
     */
    protected $orderReferenceGenerator;

    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $salesConfiguration;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var array<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface>
     */
    protected $orderExpanderPreSavePlugins;

    /**
     * @var array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface>
     */
    protected $orderPostSavePlugins;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array<int>
     */
    protected $idCountryCache = [];

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToStoreInterface $storeFacade
     * @param \Spryker\Zed\Sales\Business\Model\Order\OrderReferenceGeneratorInterface $orderReferenceGenerator
     * @param \Spryker\Zed\Sales\SalesConfig $salesConfiguration
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToLocaleInterface $localeFacade
     * @param array<\Spryker\Zed\Sales\Dependency\Plugin\OrderExpanderPreSavePluginInterface> $orderExpanderPreSavePlugins
     * @param array<\Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface> $orderPostSavePlugins
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesToCountryInterface $countryFacade,
        SalesToStoreInterface $storeFacade,
        OrderReferenceGeneratorInterface $orderReferenceGenerator,
        SalesConfig $salesConfiguration,
        SalesToLocaleInterface $localeFacade,
        $orderExpanderPreSavePlugins,
        array $orderPostSavePlugins,
        SalesEntityManagerInterface $entityManager
    ) {
        $this->countryFacade = $countryFacade;
        $this->storeFacade = $storeFacade;
        $this->orderReferenceGenerator = $orderReferenceGenerator;
        $this->salesConfiguration = $salesConfiguration;
        $this->localeFacade = $localeFacade;
        $this->orderExpanderPreSavePlugins = $orderExpanderPreSavePlugins;
        $this->orderPostSavePlugins = $orderPostSavePlugins;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->assertOrderRequirements($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($quoteTransfer, $saveOrderTransfer) {
            $this->saveOrderTransaction($quoteTransfer, $saveOrderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    protected function saveOrderTransaction(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $salesOrderEntityTransfer = $this->saveOrderEntity($quoteTransfer);
        $saveOrderTransfer = $this->hydrateSaveOrderTransfer($saveOrderTransfer, $quoteTransfer, $salesOrderEntityTransfer);
        $this->executeOrderPostSavePlugins($saveOrderTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function executeOrderPostSavePlugins(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        foreach ($this->orderPostSavePlugins as $orderPostSavePlugin) {
            $orderPostSavePlugin->execute($saveOrderTransfer, $quoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function saveOrderEntity(QuoteTransfer $quoteTransfer): SpySalesOrderEntityTransfer
    {
        $salesOrderEntityTransfer = new SpySalesOrderEntityTransfer();
        $salesOrderEntityTransfer = $this->hydrateSalesOrderEntityTransfer($quoteTransfer, $salesOrderEntityTransfer);
        $salesOrderEntityTransfer = $this->hydrateAddresses($quoteTransfer, $salesOrderEntityTransfer);
        $salesOrderEntityTransfer = $this->addLocale($salesOrderEntityTransfer);
        $salesOrderEntityTransfer = $this->entityManager->saveOrderEntity($salesOrderEntityTransfer);

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function hydrateSalesOrderEntityTransfer(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer {
        $salesOrderEntityTransfer->setCustomerReference($quoteTransfer->getCustomer()->getCustomerReference());
        $salesOrderEntityTransfer = $this->hydrateSalesOrderCustomer($quoteTransfer, $salesOrderEntityTransfer);
        $salesOrderEntityTransfer->setPriceMode($quoteTransfer->getPriceMode());
        $salesOrderEntityTransfer->setStore($this->storeFacade->getCurrentStore()->getName());
        $salesOrderEntityTransfer->setCurrencyIsoCode($quoteTransfer->getCurrency()->getCode());
        $salesOrderEntityTransfer->setOrderReference($this->orderReferenceGenerator->generateOrderReference($quoteTransfer));
        $salesOrderEntityTransfer->setIsTest($this->salesConfiguration->isTestOrder($quoteTransfer));

        $salesOrderEntityTransfer = $this->executeOrderExpanderPreSavePlugins($quoteTransfer, $salesOrderEntityTransfer);

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function hydrateSalesOrderCustomer(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer {
        $customerTransfer = $quoteTransfer->getCustomer();
        $customerData = $customerTransfer->modifiedToArray();

        if (isset($customerData['created_at'])) {
            unset($customerData['created_at']);
        }

        if (isset($customerData['updated_at'])) {
            unset($customerData['updated_at']);
        }

        $salesOrderEntityTransfer->fromArray($customerData, true);

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function executeOrderExpanderPreSavePlugins(
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SpySalesOrderEntityTransfer {
        foreach ($this->orderExpanderPreSavePlugins as $preSaveHydrateOrderPlugin) {
            $salesOrderEntityTransfer = $preSaveHydrateOrderPlugin->expand($salesOrderEntityTransfer, $quoteTransfer);
        }

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function hydrateAddresses(QuoteTransfer $quoteTransfer, SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrderEntityTransfer
    {
        $billingAddressEntityTransfer = $this->saveSalesOrderAddress($quoteTransfer->getBillingAddress());
        $salesOrderEntityTransfer->setBillingAddress($billingAddressEntityTransfer);
        $salesOrderEntityTransfer->setFkSalesOrderAddressBilling($billingAddressEntityTransfer->getIdSalesOrderAddress());

        if ($quoteTransfer->getShippingAddress() !== null && $quoteTransfer->getShippingAddress()->getFirstName() !== null) {
            $shippingAddressEntityTransfer = $this->saveSalesOrderAddress($quoteTransfer->getShippingAddress());
            $salesOrderEntityTransfer->setShippingAddress($shippingAddressEntityTransfer);
            $salesOrderEntityTransfer->setFkSalesOrderAddressShipping($shippingAddressEntityTransfer->getIdSalesOrderAddress());
        }

        return $salesOrderEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer
     */
    protected function saveSalesOrderAddress(AddressTransfer $addressTransfer): SpySalesOrderAddressEntityTransfer
    {
        $salesOrderAddressEntityTransfer = $this->saveSalesOrderAddressEntity($addressTransfer);
        $addressTransfer->setIdSalesOrderAddress($salesOrderAddressEntityTransfer->getIdSalesOrderAddress());

        return $salesOrderAddressEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer
     */
    protected function saveSalesOrderAddressEntity(AddressTransfer $addressTransfer): SpySalesOrderAddressEntityTransfer
    {
        $salesOrderAddressEntityTransfer = new SpySalesOrderAddressEntityTransfer();
        $salesOrderAddressEntityTransfer = $this->hydrateSalesOrderAddress($addressTransfer, $salesOrderAddressEntityTransfer);
        $salesOrderAddressEntityTransfer = $this->entityManager->saveSalesOrderAddressEntity($salesOrderAddressEntityTransfer);

        return $salesOrderAddressEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderAddressEntityTransfer
     */
    protected function hydrateSalesOrderAddress(
        AddressTransfer $addressTransfer,
        SpySalesOrderAddressEntityTransfer $salesOrderAddressEntityTransfer
    ): SpySalesOrderAddressEntityTransfer {
        $salesOrderAddressEntityTransfer->fromArray($addressTransfer->toArray(), true);
        if (!$salesOrderAddressEntityTransfer->getFkCountry()) {
            $salesOrderAddressEntityTransfer->setFkCountry(
                $this->getIdCountryForAddress($addressTransfer)
            );
        }

        return $salesOrderAddressEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return int
     */
    protected function getIdCountryForAddress(AddressTransfer $addressTransfer): int
    {
        $iso2code = $addressTransfer->getIso2CodeOrFail();

        if (!isset($this->idCountryCache[$iso2code])) {
            $this->idCountryCache[$iso2code] = $this->countryFacade->getIdCountryByIso2Code($iso2code);
        }

        return $this->idCountryCache[$iso2code];
    }

    /**
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    protected function addLocale(SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrderEntityTransfer
    {
        return $salesOrderEntityTransfer->setFkLocale($this->localeFacade->getCurrentLocale()->getIdLocale());
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected function hydrateSaveOrderTransfer(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer,
        SpySalesOrderEntityTransfer $salesOrderEntityTransfer
    ): SaveOrderTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saveOrderTransfer->addOrderItem(clone $itemTransfer);
        }

        return $saveOrderTransfer->fromArray($salesOrderEntityTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertOrderRequirements(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer->requireItems()->requireTotals();
    }
}
