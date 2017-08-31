<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationQuery;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainer getQueryContainer()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 */
class GiftCardPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function createGiftCardQuery()
    {
        return SpyGiftCardQuery::create();
    }

    /**
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function createSalesOrderGiftCardQuery()
    {
        return SpyPaymentGiftCardQuery::create();
    }

    /**
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationQuery
     */
    public function createSpyGiftCardProductAbstractConfigurationQuery()
    {
        return SpyGiftCardProductAbstractConfigurationQuery::create();
    }

}
