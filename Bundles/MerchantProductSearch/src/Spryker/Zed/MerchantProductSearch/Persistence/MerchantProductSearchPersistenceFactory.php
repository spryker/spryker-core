<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductSearch\MerchantProductSearchDependencyProvider;
use Spryker\Zed\MerchantProductSearch\Persistence\Mapper\MerchantProductAbstractMapper;

/**
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 * @method \Spryker\Zed\MerchantProductSearch\Persistence\MerchantProductSearchRepositoryInterface getRepository()
 */
class MerchantProductSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductSearch\Persistence\Mapper\MerchantProductAbstractMapper
     */
    public function createMerchantProductAbstractMapper(): MerchantProductAbstractMapper
    {
        return new MerchantProductAbstractMapper();
    }

    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    public function getMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return $this->getProvidedDependency(MerchantProductSearchDependencyProvider::PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT);
    }
}
