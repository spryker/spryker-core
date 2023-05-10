<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface;
use Spryker\Zed\ServicePointStorage\ServicePointStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePointStorage\ServicePointStorageConfig getConfig()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ServicePointStorage\Business\ServicePointStorageFacadeInterface getFacade()
 */
class ServicePointStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointStorageToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointStorageDependencyProvider::FACADE_SERVICE_POINT);
    }
}
