<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalancePersistenceFactory getFactory()
 */
class GiftCardBalanceQueryContainer extends AbstractQueryContainer implements GiftCardBalanceQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    public function queryGiftCardBalanceLog()
    {
        return $this->getFactory()->createGiftCardBalanceLogQuery();
    }

    /**
     * @api
     *
     * @param string $giftCardCode
     *
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    public function queryBalanceLogEntries($giftCardCode)
    {
        return $this
            ->getFactory()
            ->createGiftCardBalanceLogQuery()
            ->useSpyGiftCardQuery()
            ->filterByCode($giftCardCode)
            ->endUse();
    }
}
