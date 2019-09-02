<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Communication;

use Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageDependencyProvider;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\ConfigurableBundleStorage\ConfigurableBundleStorageConfig getConfig()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Persistence\ConfigurableBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ConfigurableBundleStorage\Business\ConfigurableBundleStorageFacadeInterface getFacade()
 */
class ConfigurableBundleStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): ConfigurableBundleStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(ConfigurableBundleStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
