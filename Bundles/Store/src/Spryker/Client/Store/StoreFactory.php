<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Store\Reader\StoreReader;

class StoreFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreReader($this->getStore());
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }
}
