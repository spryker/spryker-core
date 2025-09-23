<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProduct\MerchantProductDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\Business\MerchantProductFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductEntityManagerInterface getEntityManager()
 */
class MerchantProductCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade(): MerchantProductToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
