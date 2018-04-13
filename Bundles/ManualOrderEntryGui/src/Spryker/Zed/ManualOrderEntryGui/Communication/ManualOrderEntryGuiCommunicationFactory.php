<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Address\AddressCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Constraint\SkuExists;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomersListType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ItemCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\OrderSourceListDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\PaymentDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ProductCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ShipmentDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\StoreDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\SummaryDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\VoucherDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\OrderSource\OrderSourceListType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment\PaymentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ItemCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment\ShipmentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store\StoreType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Summary\SummaryType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Voucher\VoucherType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCheckoutFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreInterface;
use Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): ManualOrderEntryGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    public function getProductFacade(): ManualOrderEntryGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    public function getCartFacade(): ManualOrderEntryGuiToCartFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeInterface
     */
    public function getDiscountFacade(): ManualOrderEntryGuiToDiscountFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ManualOrderEntryGuiToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): ManualOrderEntryGuiToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ManualOrderEntryGuiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): ManualOrderEntryGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    public function getPaymentFacade(): ManualOrderEntryGuiToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCheckoutFacadeInterface
     */
    public function getCheckoutFacade(): ManualOrderEntryGuiToCheckoutFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CHECKOUT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCalculationFacadeInterface
     */
    public function getCalculationFacade(): ManualOrderEntryGuiToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToManualOrderEntryFacadeInterface
     */
    public function getManualOrderEntryFacade(): ManualOrderEntryGuiToManualOrderEntryFacadeInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_MANUAL_ORDER_ENTRY);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer(): ManualOrderEntryGuiToCustomerQueryContainerInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreInterface
     */
    public function getStore(): ManualOrderEntryGuiToStoreInterface
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    public function getManualOrderEntryFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_MANUAL_ORDER_ENTRY_FORM);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    public function getManualOrderEntryFilteredFormPlugins(Request $request, $quoteTransfer)
    {
        $formPlugins = $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_MANUAL_ORDER_ENTRY_FORM);

        return $this->createStepEngine()->filterFormPlugins($formPlugins, $request, $quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine
     */
    public function createStepEngine(): StepEngine
    {
        return new StepEngine($this->getConfig()->getNextStepName());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider
     */
    public function createCustomersListDataProvider(Request $request): CustomersListDataProvider
    {
        return new CustomersListDataProvider(
            $this->getCustomerQueryContainer(),
            $request
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomersListForm(Request $request, $quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createCustomersListDataProvider($request);

        return $this->getFormFactory()->create(
            CustomersListType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\OrderSourceListDataProvider
     */
    public function createOrderSourceListDataProvider()
    {
        return new OrderSourceListDataProvider(
            $this->getManualOrderEntryFacade()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOrderSourceListForm(Request $request, $quoteTransfer)
    {
        $formDataProvider = $this->createOrderSourceListDataProvider();

        return $this->getFormFactory()->create(
            OrderSourceListType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider
     */
    public function createCustomerDataProvider(): CustomerDataProvider
    {
        return new CustomerDataProvider();
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider $customerFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(CustomerDataProvider $customerFormDataProvider): FormInterface
    {
        $customerTransfer = new CustomerTransfer();

        return $this->getFormFactory()->create(
            CustomerType::class,
            $customerFormDataProvider->getData($customerTransfer),
            $customerFormDataProvider->getOptions($customerTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\AddressCollectionDataProvider
     */
    public function createAddressCollectionDataProvider(): AddressCollectionDataProvider
    {
        return new AddressCollectionDataProvider($this->getStore());
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressCollectionForm($quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createAddressCollectionDataProvider();

        return $this->getFormFactory()->create(
            AddressCollectionType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\StoreDataProvider
     */
    public function createStoreDataProvider(): StoreDataProvider
    {
        return new StoreDataProvider(
            $this->getCurrencyFacade()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createStoreForm(Request $request, $quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createStoreDataProvider();

        return $this->getFormFactory()->create(
            StoreType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ProductCollectionDataProvider
     */
    public function createProductsCollectionDataProvider(): ProductCollectionDataProvider
    {
        return new ProductCollectionDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductsCollectionForm($quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createProductsCollectionDataProvider();

        return $this->getFormFactory()->create(
            ProductCollectionType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    public function createSkuExistsConstraint(): Constraint
    {
        return new SkuExists([
            SkuExists::OPTION_PRODUCT_FACADE => $this->getProductFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ItemCollectionDataProvider
     */
    public function createItemsCollectionDataProvider(): ItemCollectionDataProvider
    {
        return new ItemCollectionDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createItemsCollectionForm($quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createItemsCollectionDataProvider();

        return $this->getFormFactory()->create(
            ItemCollectionType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\VoucherDataProvider
     */
    public function createVoucherDataProvider(): VoucherDataProvider
    {
        return new VoucherDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherForm($quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createVoucherDataProvider();

        return $this->getFormFactory()->create(
            VoucherType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ShipmentDataProvider
     */
    public function createShipmentDataProvider(): ShipmentDataProvider
    {
        return new ShipmentDataProvider(
            $this->getShipmentFacade(),
            $this->getMoneyFacade()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createShipmentForm(Request $request, $quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createShipmentDataProvider();

        return $this->getFormFactory()->create(
            ShipmentType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\PaymentDataProvider
     */
    public function createPaymentDataProvider(): PaymentDataProvider
    {
        return new PaymentDataProvider(
            $this->getPaymentMethodSubFormPlugins()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createPaymentForm(Request $request, $quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createPaymentDataProvider();

        return $this->getFormFactory()->create(
            PaymentType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGuiExtension\Dependency\Plugin\PaymentSubFormPluginInterface[]
     */
    public function getPaymentMethodSubFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\SummaryDataProvider
     */
    public function createSummaryDataProvider(): SummaryDataProvider
    {
        return new SummaryDataProvider(
            $this->getCalculationFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSummaryForm($quoteTransfer): FormInterface
    {
        $formDataProvider = $this->createSummaryDataProvider();

        return $this->getFormFactory()->create(
            SummaryType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    public function getQuoteExpanderPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_QUOTE_EXPANDER);
    }
}
