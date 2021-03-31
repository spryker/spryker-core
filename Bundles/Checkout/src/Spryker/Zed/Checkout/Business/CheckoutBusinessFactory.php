<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business;

use Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface;
use Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyProviderInterface;
use Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflow;
use Spryker\Zed\Checkout\CheckoutDependencyProvider;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToQuoteFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Checkout\CheckoutConfig getConfig()
 */
class CheckoutBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Checkout\Business\Workflow\CheckoutWorkflowInterface
     */
    public function createCheckoutWorkflow()
    {
        return new CheckoutWorkflow(
            $this->getOmsFacade(),
            $this->getQuoteFacade(),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_POST_HOOKS),
            $this->getProvidedDependency(CheckoutDependencyProvider::CHECKOUT_PRE_SAVE_HOOKS)
        );
    }

    /**
     * @return \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToOmsFacadeInterface
     */
    protected function getOmsFacade()
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToQuoteFacadeInterface
     */
    protected function getQuoteFacade(): CheckoutToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(CheckoutDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyProviderInterface
     */
    protected function createStorageStrategyProvider(): StorageStrategyProviderInterface
    {
        return new StorageStrategyProvider(
            $this->getQuoteFacade(),
            $this->getStorageStrategyList()
        );
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface[]
     */
    protected function getStorageStrategyList(): array
    {
        return [
            $this->createSessionStorageStrategy(),
            $this->createDatabaseStorageStrategy(),
        ];
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface
     */
    protected function createSessionStorageStrategy(): StorageStrategyInterface
    {
        return new SessionStorageStrategy();
    }

    /**
     * @return \Spryker\Zed\Checkout\Business\StorageStrategy\StorageStrategyInterface
     */
    protected function createDatabaseStorageStrategy(): StorageStrategyInterface
    {
        return new DatabaseStorageStrategy(
            $this->getQuoteFacade()
        );
    }
}
