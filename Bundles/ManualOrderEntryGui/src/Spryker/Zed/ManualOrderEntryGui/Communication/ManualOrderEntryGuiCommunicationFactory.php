<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Checkout\CheckoutForm;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider;
use Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider
     */
    public function createCheckoutFormDataProvider()
    {
        return new CheckoutFormDataProvider(
            $this->getCustomerQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Dependency\QueryContainer\ManualOrderEntryGuiToCustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @param \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CheckoutFormDataProvider $checkoutFormDataProvider
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCheckoutForm(CheckoutFormDataProvider $checkoutFormDataProvider)
    {
        return $this->getFormFactory()->create(
            CheckoutForm::class,
            $checkoutFormDataProvider->getData(),
            $checkoutFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\CheckoutFormPluginInterface[]
     */
    public function getCheckoutFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderEntryGuiDependencyProvider::PLUGINS_CHECKOUT_FORM);
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\Customer\CustomerType
     */
    public function createCustomerType()
    {
        return new CustomerType();
    }

    /**
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\CustomerDataProvider
     */
    public function createCustomerDataProvider()
    {
        return new CustomerDataProvider(
            $this->getCustomerQueryContainer()
        );
    }

}
