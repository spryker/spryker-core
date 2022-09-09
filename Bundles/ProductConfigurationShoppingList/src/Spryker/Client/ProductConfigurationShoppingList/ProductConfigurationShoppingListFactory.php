<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationShoppingList;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfigurationShoppingList\Adder\ProductConfigurationAdder;
use Spryker\Client\ProductConfigurationShoppingList\Adder\ProductConfigurationAdderInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface;
use Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;
use Spryker\Client\ProductConfigurationShoppingList\Expander\ProductConfigurationExpander;
use Spryker\Client\ProductConfigurationShoppingList\Expander\ProductConfigurationExpanderInterface;
use Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessorInterface;
use Spryker\Client\ProductConfigurationShoppingList\Replicator\ProductConfigurationReplicator;
use Spryker\Client\ProductConfigurationShoppingList\Replicator\ProductConfigurationReplicatorInterface;
use Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolverInterface;
use Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdater;
use Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdaterInterface;
use Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidator;
use Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductConfigurationShoppingListFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Resolver\ProductConfiguratorRedirectResolverInterface
     */
    public function createProductConfiguratorRedirectResolver(): ProductConfiguratorRedirectResolverInterface
    {
        return new ProductConfiguratorRedirectResolver(
            $this->getShoppingListClient(),
            $this->getProductConfigurationClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Expander\ProductConfigurationExpanderInterface
     */
    public function createProductConfigurationExpander(): ProductConfigurationExpanderInterface
    {
        return new ProductConfigurationExpander(
            $this->getProductConfigurationStorageClient(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorResponseValidator(
            $this->getProductConfigurationClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfigurationClient(),
            $this->createProductConfiguratorResponseValidator(),
            $this->createShoppingListItemProductConfigurationUpdater(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Updater\ShoppingListItemProductConfigurationUpdaterInterface
     */
    public function createShoppingListItemProductConfigurationUpdater(): ShoppingListItemProductConfigurationUpdaterInterface
    {
        return new ShoppingListItemProductConfigurationUpdater(
            $this->getShoppingListClient(),
            $this->getCustomerClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Adder\ProductConfigurationAdderInterface
     */
    public function createProductConfigurationAdder(): ProductConfigurationAdderInterface
    {
        return new ProductConfigurationAdder(
            $this->getProductConfigurationStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Replicator\ProductConfigurationReplicatorInterface
     */
    public function createProductConfigurationReplicator(): ProductConfigurationReplicatorInterface
    {
        return new ProductConfigurationReplicator();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationShoppingListToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToShoppingListClientInterface
     */
    public function getShoppingListClient(): ProductConfigurationShoppingListToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToProductConfigurationClientInterface
     */
    public function getProductConfigurationClient(): ProductConfigurationShoppingListToProductConfigurationClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::CLIENT_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Client\ProductConfigurationShoppingListToCustomerClientInterface
     */
    public function getCustomerClient(): ProductConfigurationShoppingListToCustomerClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationShoppingListToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
