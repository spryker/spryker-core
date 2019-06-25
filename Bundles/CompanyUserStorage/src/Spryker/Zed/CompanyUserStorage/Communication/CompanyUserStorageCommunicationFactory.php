<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Communication;

use Spryker\Zed\CompanyUserStorage\CompanyUserStorageDependencyProvider;
use Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUserStorage\Business\CompanyUserStorageFacadeInterface getFacade()
 */
class CompanyUserStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): CompanyUserStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
