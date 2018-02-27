<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business\Checker;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface;

class GiftCardBalanceChecker implements GiftCardBalanceCheckerInterface
{
    /**
     * @var \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface
     */
    protected $giftCardBalanceQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface $giftCardBalanceQueryContainer
     */
    public function __construct(GiftCardBalanceQueryContainerInterface $giftCardBalanceQueryContainer)
    {
        $this->giftCardBalanceQueryContainer = $giftCardBalanceQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function hasPositiveBalance(GiftCardTransfer $giftCardTransfer)
    {
        return $this->getRemainingValue($giftCardTransfer) > 0;
    }

    /**
     * @param string $code
     *
     * @return int
     */
    protected function getTransactionLogBalanceSum($code)
    {
        $giftCardBalanceEntries = $this->giftCardBalanceQueryContainer->queryBalanceLogEntries($code)->find();
        $sum = 0;

        foreach ($giftCardBalanceEntries as $balanceEntry) {
            $sum += $balanceEntry->getValue();
        }

        return $sum;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return int
     */
    public function getRemainingValue(GiftCardTransfer $giftCardTransfer)
    {
        $transactionLogBalanceSum = $this->getTransactionLogBalanceSum($giftCardTransfer->getCode());

        return $giftCardTransfer->getValue() - $transactionLogBalanceSum;
    }
}
