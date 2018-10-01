<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface;
use Spryker\Client\QuickOrder\ProductConcreteExpander\ProductConcreteExpander;
use Spryker\Client\QuickOrder\ProductConcreteExpander\ProductConcreteExpanderInterface;
use Spryker\Client\QuickOrder\ProductConcretePriceReader\ProductConcretePriceReader;
use Spryker\Client\QuickOrder\ProductConcretePriceReader\ProductConcretePriceReaderInterface;
use Spryker\Client\QuickOrder\ProductQuantityRestrictionsValidator\ProductQuantityRestrictionsValidator;
use Spryker\Client\QuickOrder\ProductQuantityRestrictionsValidator\ProductQuantityRestrictionsValidatorInterface;

class QuickOrderFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuickOrder\ProductConcreteExpander\ProductConcreteExpanderInterface
     */
    public function createProductConcreteExpander(): ProductConcreteExpanderInterface
    {
        return new ProductConcreteExpander($this->getProductConcreteExpanderPlugins());
    }

    /**
     * @return \Spryker\Client\QuickOrder\ProductConcretePriceReader\ProductConcretePriceReaderInterface
     */
    public function createProductConcretePriceReader(): ProductConcretePriceReaderInterface
    {
        return new ProductConcretePriceReader(
            $this->getPriceProductClient(),
            $this->getPriceProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\QuickOrder\ProductQuantityRestrictionsValidator\ProductQuantityRestrictionsValidatorInterface
     */
    public function createProductQuantityRestrictionsValidator(): ProductQuantityRestrictionsValidatorInterface
    {
        return new ProductQuantityRestrictionsValidator(
            $this->getProductQuantityClient(),
            $this->getProductQuantityStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface
     */
    public function getPriceProductClient(): QuickOrderToPriceProductClientInterface
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): QuickOrderToPriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityClientInterface
     */
    public function getProductQuantityClient(): QuickOrderToProductQuantityClientInterface
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::CLIENT_PRODUCT_QUANTITY);
    }

    /**
     * @return \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): QuickOrderToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }
}
