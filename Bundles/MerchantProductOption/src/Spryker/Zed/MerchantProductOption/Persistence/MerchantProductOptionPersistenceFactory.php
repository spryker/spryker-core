<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption\Persistence;

use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantProductOption\Persistence\Mapper\MerchantProductOptionGroupMapper;

/**
 * @method \Spryker\Zed\MerchantProductOption\MerchantProductOptionConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOption\Persistence\MerchantProductOptionRepositoryInterface getRepository()
 */
class MerchantProductOptionPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantProductOption\Persistence\Mapper\MerchantProductOptionGroupMapper
     */
    public function createMerchantProductOptionGroupMapper(): MerchantProductOptionGroupMapper
    {
        return new MerchantProductOptionGroupMapper();
    }

    /**
     * @return \Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery<mixed>
     */
    public function getMerchantProductOptionGroupQuery(): SpyMerchantProductOptionGroupQuery
    {
        return SpyMerchantProductOptionGroupQuery::create();
    }
}
