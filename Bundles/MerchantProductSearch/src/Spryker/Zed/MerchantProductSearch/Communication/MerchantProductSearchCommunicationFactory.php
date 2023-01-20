<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToMerchantProductFacadeInterface;
use Spryker\Zed\MerchantProductSearch\MerchantProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductSearch\Business\MerchantProductSearchFacadeInterface getFacade()
 */
class MerchantProductSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToMerchantProductFacadeInterface
     */
    public function getMerchantProductFacade(): MerchantProductSearchToMerchantProductFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductSearchDependencyProvider::FACADE_MERCHANT_PRODUCT);
    }
}
