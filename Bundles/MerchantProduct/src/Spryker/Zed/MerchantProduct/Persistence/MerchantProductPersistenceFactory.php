<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Persistence;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProduct\MerchantProductConfig getConfig()
 */
class MerchantProductPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery
     */
    public function createMerchantProductAbstractPropelQuery(): SpyMerchantProductAbstractQuery
    {
        return SpyMerchantProductAbstractQuery::create();
    }
}
