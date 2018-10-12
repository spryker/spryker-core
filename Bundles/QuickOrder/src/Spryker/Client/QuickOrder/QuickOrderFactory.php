<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToPriceProductStorageClientInterface;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpander;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface;
use Spryker\Client\QuickOrder\Reader\ProductPriceReader;
use Spryker\Client\QuickOrder\Reader\ProductPriceReaderInterface;

class QuickOrderFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface
     */
    public function createProductConcreteExpander(): ProductConcreteExpanderInterface
    {
        return new ProductConcreteExpander($this->getProductConcreteExpanderPlugins());
    }

    /**
     * @return \Spryker\Client\QuickOrder\Reader\ProductPriceReaderInterface
     */
    public function createProductPriceReader(): ProductPriceReaderInterface
    {
        return new ProductPriceReader(
            $this->getPriceProductClient(),
            $this->getPriceProductStorageClient()
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
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }
}
