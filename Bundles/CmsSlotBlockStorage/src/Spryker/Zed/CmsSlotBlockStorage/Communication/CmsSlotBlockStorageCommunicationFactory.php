<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockStorage\Communication;

use Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageDependencyProvider;
use Spryker\Zed\CmsSlotBlockStorage\Dependency\Facade\CmsSlotBlockStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CmsSlotBlockStorage\CmsSlotBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Persistence\CmsSlotBlockStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotBlockStorage\Business\CmsSlotBlockStorageFacadeInterface getFacade()
 */
class CmsSlotBlockStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotBlockStorage\Dependency\Facade\CmsSlotBlockStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): CmsSlotBlockStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotBlockStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
