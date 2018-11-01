<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationQuery;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfigurationQuery;
use Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
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

    /**
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfigurationQuery
     */
    public function createSpyGiftCardProductConfigurationQuery()
    {
        return SpyGiftCardProductConfigurationQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    public function createSpySalesOrderItemGiftCardQuery()
    {
        return SpySalesOrderItemGiftCardQuery::create();
    }
}
