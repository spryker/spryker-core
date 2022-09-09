<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationShoppingList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Checker\ProductConfigurationChecker;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Checker\ProductConfigurationCheckerInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Expander\ProductConfigurationExpander;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Expander\ProductConfigurationExpanderInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Replicator\ProductConfigurationReplicator;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Replicator\ProductConfigurationReplicatorInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Writer\ProductConfigurationWriter;
use Spryker\Zed\ProductConfigurationShoppingList\Business\Writer\ProductConfigurationWriterInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade\ProductConfigurationShoppingListToProductConfigurationFacadeInterface;
use Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface;
use Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListDependencyProvider;

/**
 * @method \Spryker\Zed\ProductConfigurationShoppingList\ProductConfigurationShoppingListConfig getConfig()
 * @method \Spryker\Zed\ProductConfigurationShoppingList\Persistence\ProductConfigurationShoppingListEntityManagerInterface getEntityManager()
 */
class ProductConfigurationShoppingListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Business\Writer\ProductConfigurationWriterInterface
     */
    public function createProductConfigurationWriter(): ProductConfigurationWriterInterface
    {
        return new ProductConfigurationWriter(
            $this->getUtilEncodingService(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Business\Replicator\ProductConfigurationReplicatorInterface
     */
    public function createProductConfigurationReplicator(): ProductConfigurationReplicatorInterface
    {
        return new ProductConfigurationReplicator();
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Business\Checker\ProductConfigurationCheckerInterface
     */
    public function createProductConfigurationChecker(): ProductConfigurationCheckerInterface
    {
        return new ProductConfigurationChecker($this->getProductConfigurationFacade());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Business\Expander\ProductConfigurationExpanderInterface
     */
    public function createProductConfigurationExpander(): ProductConfigurationExpanderInterface
    {
        return new ProductConfigurationExpander($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Facade\ProductConfigurationShoppingListToProductConfigurationFacadeInterface
     */
    public function getProductConfigurationFacade(): ProductConfigurationShoppingListToProductConfigurationFacadeInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::FACADE_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationShoppingList\Dependency\Service\ProductConfigurationShoppingListToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductConfigurationShoppingListToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationShoppingListDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
