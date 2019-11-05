<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProfileStorage\Business\MerchantProfileStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantProfileStorage\Persistence\MerchantProfileStorageRepositoryInterface getRepository()
 */
class MerchantProfileStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProfileStorage\Dependency\Facade\MerchantProfileStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProfileStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProfileStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
