<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Communication;

use Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Persistence\CmsBlockCategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\CmsBlockCategoryStorageConfig getConfig()
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageFacadeInterface getFacade()
 */
class CmsBlockCategoryStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CmsBlockCategoryStorage\Dependency\Facade\CmsBlockCategoryStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(CmsBlockCategoryStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
