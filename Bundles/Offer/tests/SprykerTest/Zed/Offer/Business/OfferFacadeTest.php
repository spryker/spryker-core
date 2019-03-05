<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Offer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Offer
 * @group Business
 * @group Facade
 * @group OfferFacadeTest
 * Add your own group annotations below this line
 */
class OfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Offer\OfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAggregateOfferItemSubtotalShouldWorkWithFloatItemQuantity()
    {
        /**
         * @var \Spryker\Zed\Offer\Business\OfferFacade $offerFacade
         */
        $offerFacade = $this->tester->getFacade();
        $quoteTransfer = new QuoteTransfer();

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSumSubtotalAggregation(150);
        $itemTransfer->setOfferFee(15);
        $itemTransfer->setQuantity(1.3);

        $quoteTransfer->addItem($itemTransfer);

        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->setItems($quoteTransfer->getItems());

        $offerFacade->aggregateOfferItemSubtotal($calculableObjectTransfer);

        $aggregatedItemTransfer = $quoteTransfer->getItems()[0];

        $this->assertIsInt($aggregatedItemTransfer->getSumSubtotalAggregation());
        $this->assertSame(170, $aggregatedItemTransfer->getSumSubtotalAggregation());
    }

    /**
     * @return void
     */
    public function testHydrateOfferWithSavingAmountShluldWorkWithFloatItemQuantity()
    {
        /**
         * @var \Spryker\Zed\Offer\Business\OfferFacade $offerFacade
         */
        $offerFacade = $this->tester->getFacade();
        $offerTransfer = new OfferTransfer();

        $quoteTranfer = new QuoteTransfer();
        $quoteTranfer->setPriceMode('NET_MODE');

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setOriginUnitNetPrice(150);
        $itemTransfer->setUnitNetPrice(104);
        $itemTransfer->setOfferDiscount(0);
        $itemTransfer->setOfferFee(15);
        $itemTransfer->setQuantity(1.7);

        $quoteTranfer->addItem($itemTransfer);
        $offerTransfer->setQuote($quoteTranfer);

        $hydratedOfferTransfer = $offerFacade->hydrateOfferWithSavingAmount($offerTransfer);

        $hydratedItemTransfer = $hydratedOfferTransfer
            ->getQuote()
            ->getItems()[0];

        $this->assertIsInt($hydratedItemTransfer->getSavingAmount());
        $this->assertSame(53, $hydratedItemTransfer->getSavingAmount());
    }
}
