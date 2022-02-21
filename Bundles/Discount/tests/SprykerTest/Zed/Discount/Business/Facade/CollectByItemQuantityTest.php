<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ClauseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group Facade
 * @group CollectByItemQuantityTest
 * Add your own group annotations below this line
 */
class CollectByItemQuantityTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Discount\DiscountBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillReturnDiscountableItemsForOneItem(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::QUANTITY => 5])
            ->build();
        $clauseTransfer = $this->createClauseTransfer();

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collectByItemQuantity($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(1, $discountableItemTransfers);
    }

    /**
     * @return void
     */
    public function testWillReturnDiscountableItemsForMultipleItems(): void
    {
        // Arrange
        $itemTransferSeedData = [
            ItemTransfer::QUANTITY => 1,
            ItemTransfer::GROUP_KEY => 'test-group-key',
        ];
        $quoteTransfer = (new QuoteBuilder())
            ->withItem($itemTransferSeedData)
            ->withAnotherItem($itemTransferSeedData)
            ->withAnotherItem($itemTransferSeedData)
            ->withAnotherItem($itemTransferSeedData)
            ->withAnotherItem($itemTransferSeedData)
            ->build();
        $clauseTransfer = $this->createClauseTransfer();

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collectByItemQuantity($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(5, $discountableItemTransfers);
    }

    /**
     * @return void
     */
    public function testWillNotReturnDiscountableItemsForItemsNotMatchingClause(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())
            ->withItem([ItemTransfer::QUANTITY => 1])
            ->build();
        $clauseTransfer = $this->createClauseTransfer();

        // Act
        $discountableItemTransfers = $this->tester->getFacade()->collectByItemQuantity($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(0, $discountableItemTransfers);
    }

    /**
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransfer(): ClauseTransfer
    {
        return (new ClauseBuilder([
            ClauseTransfer::FIELD => 'item-quantity',
            ClauseTransfer::VALUE => '5',
            ClauseTransfer::OPERATOR => '=',
            ClauseTransfer::ACCEPTED_TYPES => [
                'number',
                'list',
            ],
        ]))->build();
    }
}
