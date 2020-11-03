<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Persistence;

use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantCategory\Persistence\Propel\Mapper\MerchantCategoryMapper;

/**
 * @method \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantCategory\MerchantCategoryConfig getConfig()
 */
class MerchantCategoryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery<\Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory>
     */
    public function getMerchantCategoryPropelQuery(): SpyMerchantCategoryQuery
    {
        return SpyMerchantCategoryQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantCategory\Persistence\Propel\Mapper\MerchantCategoryMapper
     */
    public function createMerchantCategoryMapper(): MerchantCategoryMapper
    {
        return new MerchantCategoryMapper();
    }
}
