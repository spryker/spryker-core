<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotStorage\Business;

use Spryker\Zed\CmsSlotStorage\Business\Publisher\CmsSlotStoragePublisher;
use Spryker\Zed\CmsSlotStorage\Business\Publisher\CmsSlotStoragePublisherInterface;
use Spryker\Zed\CmsSlotStorage\CmsSlotStorageDependencyProvider;
use Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CmsSlotStorage\CmsSlotStorageConfig getConfig()
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CmsSlotStorage\Persistence\CmsSlotStorageEntityManagerInterface getEntityManager()
 */
class CmsSlotStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CmsSlotStorage\Business\Publisher\CmsSlotStoragePublisherInterface
     */
    public function createCmsSlotStoragePublisher(): CmsSlotStoragePublisherInterface
    {
        return new CmsSlotStoragePublisher(
            $this->getCmsSlotFacade(),
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CmsSlotStorage\Dependency\Facade\CmsSlotStorageToCmsSlotFacadeInterface
     */
    public function getCmsSlotFacade(): CmsSlotStorageToCmsSlotFacadeInterface
    {
        return $this->getProvidedDependency(CmsSlotStorageDependencyProvider::FACADE_CMS_SLOT);
    }
}
