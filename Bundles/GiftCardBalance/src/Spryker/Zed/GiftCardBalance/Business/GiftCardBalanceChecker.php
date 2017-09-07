<?php

namespace Spryker\Zed\GiftCardBalance\Business;

use Generated\Shared\Transfer\GiftCardTransfer;

class GiftCardBalanceChecker
{

    /**
     * @param GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function hasPositiveBalance(GiftCardTransfer $giftCardTransfer)
    {
        $transactionLogBalanceSum = $this->getTransactionLogBalanceSum($giftCardTransfer->getCode());

        return $giftCardTransfer->getValue() - $transactionLogBalanceSum > 0;
    }

    /**
     * @param string $code
     *
     * @return int
     */
    protected function getTransactionLogBalanceSum($code)
    {
        return 0;
    }

}