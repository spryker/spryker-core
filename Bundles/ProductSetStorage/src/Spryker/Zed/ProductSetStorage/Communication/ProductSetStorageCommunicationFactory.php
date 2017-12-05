<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 */
class ProductSetStorageCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\Service\ProductSetStorageToUtilSynchronizationInterface
     */
    public function getUtilSynchronization()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::SERVICE_UTIL_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::STORE);
    }

}
