<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Store\Reader\KernelStoreReader;

class StoreFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Store\Reader\KernelStoreReaderInterface
     */
    public function createKernelStoreReader()
    {
        return new KernelStoreReader($this->getStore());
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToKernelStoreInterface
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }
}
