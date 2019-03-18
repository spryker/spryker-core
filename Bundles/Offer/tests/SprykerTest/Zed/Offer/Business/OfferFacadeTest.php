<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Offer\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
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
     * @dataProvider aggregateOfferItemSubtotalShouldWorkWithFloatItemQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param int $expectedResult
     *
     * @return void
     */
    public function testAggregateOfferItemSubtotalShouldWorkWithFloatQuantity(CalculableObjectTransfer $calculableObjectTransfer, int $expectedResult): void
    {
        /**
         * @var \Spryker\Zed\Offer\Business\OfferFacade $offerFacade
         */
        $offerFacade = $this->tester->getFacade();
        $aggregatedItemTransfer = $calculableObjectTransfer->getItems()[0];

        $offerFacade->aggregateOfferItemSubtotal($calculableObjectTransfer);

        $this->assertIsInt($aggregatedItemTransfer->getSumSubtotalAggregation());
        $this->assertSame($expectedResult, $aggregatedItemTransfer->getSumSubtotalAggregation());
    }

    /**
     * @return array
     */
    public function aggregateOfferItemSubtotalShouldWorkWithFloatItemQuantityDataProvider(): array
    {
        return [
            'normal fee and qty' => $this->getDataForAggregateOfferItemSubtotal(1.3, 100, 200, 330),
            'extremely small offer fee and qty' => $this->getDataForAggregateOfferItemSubtotal(0.5, 1, 2, 3),
            'below 0 offer fee and qty' => $this->getDataForAggregateOfferItemSubtotal(0.4, 1, 2, 2),
        ];
    }

    /**
     * @param float $quantity
     * @param int $offerFee
     * @param int $sumSubtotalAggregation
     * @param int $expectedResult
     *
     * @return array
     */
    protected function getDataForAggregateOfferItemSubtotal(
        float $quantity,
        int $offerFee,
        int $sumSubtotalAggregation,
        int $expectedResult
    ): array {
        $quoteTransfer = (new QuoteBuilder())->build();
        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::QUANTITY => $quantity,
            ItemTransfer::OFFER_FEE => $offerFee,
            ItemTransfer::SUM_SUBTOTAL_AGGREGATION => $sumSubtotalAggregation,
        ])->build();

        $quoteTransfer->addItem($itemTransfer);
        $calculableObjectTransfer = new CalculableObjectTransfer();
        $calculableObjectTransfer->setItems($quoteTransfer->getItems());

        return [$calculableObjectTransfer, $expectedResult];
    }

    /**
     * @dataProvider hydrateOfferWithSavingAmountShouldWorkWithFloatItemQuantityDataProvider
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param int $expectedResult
     *
     * @return void
     */
    public function testHydrateOfferWithSavingAmountShouldWorkWithFloatItemQuantity(OfferTransfer $offerTransfer, int $expectedResult)
    {
        /**
         * @var \Spryker\Zed\Offer\Business\OfferFacade $offerFacade
         */
        $offerFacade = $this->tester->getFacade();

        $hydratedOfferTransfer = $offerFacade->hydrateOfferWithSavingAmount($offerTransfer);

        $hydratedItemTransfer = $hydratedOfferTransfer
            ->getQuote()
            ->getItems()[0];

        $this->assertIsInt($hydratedItemTransfer->getSavingAmount());
        $this->assertSame($expectedResult, $hydratedItemTransfer->getSavingAmount());
    }

    /**
     * @return array
     */
    public function hydrateOfferWithSavingAmountShouldWorkWithFloatItemQuantityDataProvider(): array
    {
        return [
            'normal prices and qty' => $this->getDataForHydrateOfferWithSavingAmount(104, 15, 1.7, 53),
            'extremely small prices and qty' => $this->getDataForHydrateOfferWithSavingAmount(124, 23, 0.6, 2),
            'below 0 prices and qty combo' => $this->getDataForHydrateOfferWithSavingAmount(124, 25, 0.3, 0),
        ];
    }

    /**
     * @param int $unitNetPrice
     * @param int $offerFee
     * @param float $quantity
     * @param int $expectedResult
     *
     * @return array
     */
    protected function getDataForHydrateOfferWithSavingAmount(int $unitNetPrice, int $offerFee, float $quantity, int $expectedResult)
    {
        $offerTransfer = new OfferTransfer();
        $quoteTransfer = (new QuoteBuilder())->seed([
            QuoteTransfer::PRICE_MODE => 'NET_MODE',
        ])->build();

        $itemTransfer = (new ItemBuilder())->seed([
            ItemTransfer::ORIGIN_UNIT_NET_PRICE => 150,
            ItemTransfer::UNIT_NET_PRICE => $unitNetPrice,
            ItemTransfer::OFFER_DISCOUNT => 0,
            ItemTransfer::OFFER_FEE => $offerFee,
            ItemTransfer::QUANTITY => $quantity,
        ])->build();

        $quoteTransfer->addItem($itemTransfer);
        $offerTransfer->setQuote($quoteTransfer);

        return [$offerTransfer, $expectedResult];
    }
}
