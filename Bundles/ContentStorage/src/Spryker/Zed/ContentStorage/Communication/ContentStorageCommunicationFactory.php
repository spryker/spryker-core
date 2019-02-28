<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication;

use Spryker\Zed\ContentStorage\ContentStorageDependencyProvider;
use Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToEventBehaviorInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 */
class ContentStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToEventBehaviorInterface
     */
    public function getEventBehaviorFacade(): ContentStorageToEventBehaviorInterface
    {
        return $this->getProvidedDependency(ContentStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
