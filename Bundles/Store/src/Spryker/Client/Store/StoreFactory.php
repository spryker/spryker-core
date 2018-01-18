<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Store\Configuration\StoreConfigurationProvider;
use Spryker\Shared\Store\Configuration\StoreConfigurationReader;

class StoreFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Store\Configuration\StoreConfigurationReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreConfigurationReader($this->createStoreConfigurationProvider());
    }

    /**
     * @return \Spryker\Shared\Store\Configuration\StoreConfigurationProviderInterface
     */
    protected function createStoreConfigurationProvider()
    {
        return new StoreConfigurationProvider($this->getStore());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }
}
