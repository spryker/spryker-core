<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard;

use Codeception\Actor;
use Generated\Shared\Transfer\GiftCardTransfer;
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
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class GiftCardBusinessTester extends Actor
{
    use _generated\GiftCardBusinessTesterActions;

    public const GIFT_CARD_CODE = 'testCode1';

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithoutGiftCard(): QuoteTransfer
    {
        return $this->createQuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithGiftCard(): QuoteTransfer
    {
        return $this->createQuoteTransfer()
            ->addGiftCard((new GiftCardTransfer())->setCode(static::GIFT_CARD_CODE));
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
