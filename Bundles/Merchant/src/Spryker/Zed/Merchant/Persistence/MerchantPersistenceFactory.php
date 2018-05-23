<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Merchant\Persistence\Mapper\MerchantMapper;
use Spryker\Zed\Merchant\Persistence\Mapper\MerchantMapperInterface;

/**
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    public function createMerchantQuery()
    {
        return SpyMerchantQuery::create();
    }

    /**
     * @return \Spryker\Zed\Merchant\Persistence\Mapper\MerchantMapperInterface
     */
    public function createMerchantMapper(): MerchantMapperInterface
    {
        return new MerchantMapper();
    }
}
