<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpander;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface;
use Spryker\Client\QuickOrder\Product\ProductConcreteResolver;
use Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface;
use Spryker\Client\QuickOrder\Validator\QuickOrderValidation;
use Spryker\Client\QuickOrder\Validator\QuickOrderValidationInterface;

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
     * @return \Spryker\Client\QuickOrder\Validator\QuickOrderValidationInterface
     */
    public function createQuickOrderValidator(): QuickOrderValidationInterface
    {
        return new QuickOrderValidation($this->getQuickOrderValidationPlugins());
    }

    /**
     * @return \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface
     */
    public function createProductConcreteResolver(): ProductConcreteResolverInterface
    {
        return new ProductConcreteResolver(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }

    /**
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface[]
     */
    public function getQuickOrderValidationPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::PLUGINS_QUICK_ORDER_VALIDATION);
    }

    /**
     * @return \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface
     */
    public function getProductStorageClient(): QuickOrderToProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
