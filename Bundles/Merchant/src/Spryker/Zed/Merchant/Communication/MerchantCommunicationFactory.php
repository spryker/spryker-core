<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface;
use Spryker\Zed\Merchant\MerchantDependencyProvider;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Business\MerchantFacadeInterface getFacade()
 */
class MerchantCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_STORE);
    }
}
