<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Persistence;

use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Persistence\MerchantProductOptionStorageRepositoryInterface getRepository()
 */
class MerchantProductOptionStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed>
     */
    public function getMerchantProductOptionGroupPropelQuery(): SpyMerchantProductOptionGroupQuery
    {
        return $this->getProvidedDependency(MerchantProductOptionStorageDependencyProvider::PROPEL_QUERY_MERCHANT_PRODUCT_OPTION_GROUP);
    }
}
