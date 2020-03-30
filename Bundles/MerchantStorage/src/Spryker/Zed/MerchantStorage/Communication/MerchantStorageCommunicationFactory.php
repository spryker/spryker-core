<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantStorage\MerchantStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantStorage\MerchantStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantStorage\Business\MerchantStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface getRepository()
 */
class MerchantStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantStorageToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantStorageDependencyProvider::FACADE_MERCHANT);
    }
}
