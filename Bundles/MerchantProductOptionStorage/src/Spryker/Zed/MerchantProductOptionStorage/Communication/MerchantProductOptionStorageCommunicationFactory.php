<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface;
use Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Business\MerchantProductOptionStorageFacadeInterface getFacade()
 */
class MerchantProductOptionStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
     */
    public function getMerchantProductOptionFacade(): MerchantProductOptionStorageToMerchantProductOptionFacadeInterface
    {
        return $this->getProvidedDependency(MerchantProductOptionStorageDependencyProvider::FACADE_MERCHANT_PRODUCT_OPTION);
    }
}
