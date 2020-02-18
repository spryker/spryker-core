<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Persistence;

use Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\MerchantOmsMapper;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantOms\Persistence\SpyMerchantOmsProcessQuery
     */
    public function createMerchantOmsProcessQuery(): SpyMerchantOmsProcessQuery
    {
        return SpyMerchantOmsProcessQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Persistence\Propel\Mapper\MerchantOmsMapper
     */
    public function createMerchantOmsMapper(): MerchantOmsMapper
    {
        return new MerchantOmsMapper();
    }
}
