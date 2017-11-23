<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Persistence;

use Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GiftCardBalance\GiftCardBalanceConfig getConfig()
 * @method \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface getQueryContainer()
 */
class GiftCardBalancePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    public function createGiftCardBalanceLogQuery()
    {
        return SpyGiftCardBalanceLogQuery::create();
    }
}
