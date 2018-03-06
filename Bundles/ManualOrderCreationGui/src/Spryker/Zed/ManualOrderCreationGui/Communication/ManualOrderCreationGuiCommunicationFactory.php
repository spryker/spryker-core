<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderCreationGui\Communication\Form\Checkout\CheckoutForm;
use Spryker\Zed\ManualOrderCreationGui\Communication\Form\Customer\CustomerType;
use Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider\CheckoutFormDataProvider;
use Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider\CustomerDataProvider;
use Spryker\Zed\ManualOrderCreationGui\ManualOrderCreationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ManualOrderCreationGui\ManualOrderCreationGuiConfig getConfig()
 */
class ManualOrderCreationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider\CheckoutFormDataProvider
     */
    public function createCheckoutFormDataProvider()
    {
        return new CheckoutFormDataProvider(
            $this->getCustomerQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ManualOrderCreationGui\Dependency\QueryContainer\ManualOrderCreationGuiToCustomerQueryContainerInterface
     */
    public function getCustomerQueryContainer()
    {
        return $this->getProvidedDependency(ManualOrderCreationGuiDependencyProvider::QUERY_CONTAINER_CUSTOMER);
    }

    /**
     * @param \Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider\CheckoutFormDataProvider $checkoutFormDataProvider
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
     * @return \Spryker\Zed\ManualOrderCreationGui\Communication\Plugin\CheckoutFormPluginInterface[]
     */
    public function getCheckoutFormPlugins()
    {
        return $this->getProvidedDependency(ManualOrderCreationGuiDependencyProvider::PLUGINS_CHECKOUT_FORM);
    }

    /**
     * @return \Spryker\Zed\ManualOrderCreationGui\Communication\Form\Customer\CustomerType
     */
    public function createCustomerType()
    {
        return new CustomerType();
    }

    /**
     * @return \Spryker\Zed\ManualOrderCreationGui\Communication\Form\DataProvider\CustomerDataProvider
     */
    public function createCustomerDataProvider()
    {
        return new CustomerDataProvider(
            $this->getCustomerQueryContainer()
        );
    }

}
