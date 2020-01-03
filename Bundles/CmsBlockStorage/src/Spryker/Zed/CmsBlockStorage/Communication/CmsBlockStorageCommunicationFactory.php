<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication;

use Spryker\Zed\CmsBlockStorage\CmsBlockStorageDependencyProvider;
use Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockStorage\CmsBlockStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageFacadeInterface getFacade()
 */
class CmsBlockStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockStorage\Dependency\Facade\CmsBlockStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): CmsBlockStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(CmsBlockStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
