<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Persistence;

interface GiftCardBalanceQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    public function queryGiftCardBalanceLog();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $giftCardCode
     *
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    public function queryBalanceLogEntries($giftCardCode);
}
