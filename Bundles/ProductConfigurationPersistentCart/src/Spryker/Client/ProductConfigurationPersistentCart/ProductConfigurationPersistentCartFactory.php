<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationPersistentCart;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationPersistentCart\Expander\ProductConfigurationInstanceCartChangeExpander;
use Spryker\Client\ProductConfigurationPersistentCart\Expander\ProductConfigurationInstanceCartChangeExpanderInterface;

class ProductConfigurationPersistentCartFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfigurationPersistentCart\Expander\ProductConfigurationInstanceCartChangeExpanderInterface
     */
    public function createProductConfigurationInstanceCartChangeExpander(): ProductConfigurationInstanceCartChangeExpanderInterface
    {
        return new ProductConfigurationInstanceCartChangeExpander(
            $this->getProductConfigurationStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationPersistentCart\Dependency\Client\ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationPersistentCartToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationPersistentCartDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }
}
