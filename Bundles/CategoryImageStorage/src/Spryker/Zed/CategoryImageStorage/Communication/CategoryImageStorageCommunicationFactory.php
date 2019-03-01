<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication;

use Spryker\Zed\CategoryImageStorage\CategoryImageStorageDependencyProvider;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToEventBehaviorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageFacadeInterface getFacade()
 */
class CategoryImageStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToEventBehaviorInterface
     */
    public function getEventBehaviorFacade(): CategoryImageStorageToEventBehaviorInterface
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
