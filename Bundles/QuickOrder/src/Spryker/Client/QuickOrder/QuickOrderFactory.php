<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpander;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface;

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
     * @return \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    public function getProductConcreteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuickOrderDependencyProvider::PLUGINS_PRODUCT_CONCRETE_EXPANDER);
    }
}
