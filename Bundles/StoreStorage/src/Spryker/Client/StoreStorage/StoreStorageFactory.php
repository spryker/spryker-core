<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class StoreStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::STORE);
    }
}
