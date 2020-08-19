<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount;

use Codeception\Actor;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class DiscountBusinessTester extends Actor
{
    use _generated\DiscountBusinessTesterActions;

    public const VOUCHER_CODE = 'testCode1';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithoutVoucherDiscount(): QuoteTransfer
    {
        return $this->createQuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithVoucherDiscount(): QuoteTransfer
    {
        return $this->createQuoteTransfer()
            ->addVoucherDiscount((new DiscountTransfer())->setVoucherCode(static::VOUCHER_CODE));
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setQuantity(3);

        return $quoteTransfer->addItem($itemTransfer);
    }
}
