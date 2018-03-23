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
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\PaymentDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ProductCollectionDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ShipmentDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\StoreDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\SummaryDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\VoucherDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Payment\PaymentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ItemCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Shipment\ShipmentType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Store\StoreType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Summary\SummaryType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Voucher\VoucherType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine;
use Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToProductFacadeInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    public function getCartFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToDiscountFacadeInterface
     */
    public function getDiscountFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCurrencyFacadeInterface
     */
    public function getCurrencyFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToPaymentFacadeInterface
     */
    public function getPaymentFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCheckoutFacadeInterface
     */
    public function getCheckoutFacade()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::FACADE_CHECKOUT);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\Service\ManualOrderEntryGuiToStoreInterface
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::STORE);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\ManualOrderEntryFormPluginInterface[]
     */
    public function getManualOrderEntryFormPlugins(Request $request, $quoteTransfer)
    {
        $formPlugins = $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_MANUAL_ORDER_ENTRY_FORM);

        return $this->createStepEngine()->filterFormPlugins($formPlugins, $request, $quoteTransfer);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Service\StepEngine
     */
    public function createStepEngine()
    {
        return new StepEngine();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomersListDataProvider
     */
    public function createCustomersListDataProvider(Request $request)
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
    public function createCustomersListForm(Request $request, $quoteTransfer)
    {
        $formDataProvider = $this->createCustomersListDataProvider($request);

        return $this->getFormFactory()->create(
            CustomersListType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider
     */
    public function createCustomerDataProvider()
    {
        return new CustomerDataProvider();
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider $customerFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCustomerForm(CustomerDataProvider $customerFormDataProvider)
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
    public function createAddressCollectionDataProvider()
    {
        return new AddressCollectionDataProvider($this->getStore());
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAddressCollectionForm($quoteTransfer)
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
    public function createStoreDataProvider()
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
    public function createStoreForm(Request $request, $quoteTransfer)
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
    public function createProductsCollectionDataProvider()
    {
        return new ProductCollectionDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductsCollectionForm($quoteTransfer)
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
    public function createSkuExistsConstraint()
    {
        return new SkuExists([
            SkuExists::OPTION_PRODUCT_FACADE => $this->getProductFacade(),
        ]);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\ItemCollectionDataProvider
     */
    public function createItemsCollectionDataProvider()
    {
        return new ItemCollectionDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createItemsCollectionForm($quoteTransfer)
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
    public function createVoucherDataProvider()
    {
        return new VoucherDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createVoucherForm($quoteTransfer)
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
    public function createShipmentDataProvider()
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
    public function createShipmentForm(Request $request, $quoteTransfer)
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
    public function createPaymentDataProvider()
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
    public function createPaymentForm(Request $request, $quoteTransfer)
    {
        $formDataProvider = $this->createPaymentDataProvider();

        return $this->getFormFactory()->create(
            PaymentType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Payment\SubFormPluginInterface[]
     */
    public function getPaymentMethodSubFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PAYMENT_SUB_FORMS);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\SummaryDataProvider
     */
    public function createSummaryDataProvider()
    {
        return new SummaryDataProvider();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSummaryForm($quoteTransfer)
    {
        $formDataProvider = $this->createSummaryDataProvider();

        return $this->getFormFactory()->create(
            SummaryType::class,
            $formDataProvider->getData($quoteTransfer),
            $formDataProvider->getOptions($quoteTransfer)
        );
    }
}
