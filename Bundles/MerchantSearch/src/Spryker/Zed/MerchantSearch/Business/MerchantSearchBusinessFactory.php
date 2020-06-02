<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface;
use Spryker\Zed\MerchantSearch\MerchantSearchDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantSearch\MerchantSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface getRepository()
 */
class MerchantSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantSearchToMerchantFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSearchDependencyProvider::FACADE_MERCHANT);
    }
}
