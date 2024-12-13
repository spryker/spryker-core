<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantDiscountConnector\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\MerchantDiscountConnector\MerchantDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantDiscountConnector
 * @group Business
 * @group Facade
 * @group CollectDiscountableItemsByMerchantReferenceTest
 * Add your own group annotations below this line
 */
class CollectDiscountableItemsByMerchantReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'test-merchant-reference-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'test-merchant-reference-2';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\Comparator\IsIn::EXPRESSION
     *
     * @var string
     */
    protected const IS_IN_EXPRESSION = 'is in';

    /**
     * @see \Spryker\Zed\Discount\Business\QueryString\Comparator\IsNotIn::EXPRESSION
     *
     * @var string
     */
    protected const IS_NOT_IN_EXPRESSION = 'is not in';

    /**
     * @var \SprykerTest\Zed\MerchantDiscountConnector\MerchantDiscountConnectorBusinessTester
     */
    protected MerchantDiscountConnectorBusinessTester $tester;

    /**
     * @dataProvider shouldReturnAllItemsMatchingClauseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $operator
     * @param string $merchantReference
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $expectedItems
     *
     * @return void
     */
    public function testShouldReturnAllItemsMatchingClause(
        QuoteTransfer $quoteTransfer,
        string $operator,
        string $merchantReference,
        array $expectedItems
    ): void {
        // Arrange
        $clauseTransfer = $this->tester->createClauseTransfer($operator, $merchantReference);

        // Act
        $collectedItems = $this->tester->getFacade()->collectDiscountableItemsByMerchantReference($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(count($expectedItems), $collectedItems);
        foreach ($expectedItems as $expectedItem) {
            $this->assertTrue($this->isItemCollected($collectedItems, $expectedItem->getSkuOrFail()));
        }
    }

    /**
     * @dataProvider shouldReturnDiscountableItemsWithCorrectUnitPriceDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $expectedUnitPrice
     *
     * @return void
     */
    public function testShouldReturnDiscountableItemsWithCorrectUnitPrice(QuoteTransfer $quoteTransfer, int $expectedUnitPrice): void
    {
        // Arrange
        $clauseTransfer = $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, static::TEST_MERCHANT_REFERENCE_1);

        // Act
        $collectedItems = $this->tester->getFacade()->collectDiscountableItemsByMerchantReference($quoteTransfer, $clauseTransfer);

        // Assert
        $this->assertCount(1, $collectedItems);
        $this->assertSame($expectedUnitPrice, $collectedItems[0]->getUnitPrice());
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredTransferPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $expectedMessage
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredTransferPropertyIsNotSet(QuoteTransfer $quoteTransfer, string $expectedMessage): void
    {
        // Arrange
        $clauseTransfer = $this->tester->createClauseTransfer(static::IS_IN_EXPRESSION, static::TEST_MERCHANT_REFERENCE_1);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Act
        $this->tester->getFacade()->collectDiscountableItemsByMerchantReference($quoteTransfer, $clauseTransfer);
    }

    /**
     * @return array<string, mixed>
     */
    protected function shouldReturnAllItemsMatchingClauseDataProvider(): array
    {
        $itemTransfer1 = (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1]))->build();
        $itemTransfer2 = (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2]))->build();
        $itemTransfer3 = (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => null]))->build();

        $quoteTransfer = (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))->build();
        $quoteTransfer->setItems(new ArrayObject([
            $itemTransfer1,
            $itemTransfer2,
            $itemTransfer3,
        ]));

        return [
            'returns all items matching the clause with "is in" operator' => [
                $quoteTransfer,
                static::IS_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE_1,
                [$itemTransfer1],
            ],
            'returns all items matching the clause with "is not in" operator' => [
                $quoteTransfer,
                static::IS_NOT_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE_1,
                [$itemTransfer2],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function shouldReturnDiscountableItemsWithCorrectUnitPriceDataProvider(): array
    {
        return [
            'Sets gross price when price mode is GROSS' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withItem([
                        ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
                        ItemTransfer::UNIT_GROSS_PRICE => 888,
                        ItemTransfer::UNIT_NET_PRICE => 777,
                    ])
                    ->build(),
                888,
            ],
            'Sets net price when price mode is NET' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_NET]))
                    ->withItem([
                        ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
                        ItemTransfer::UNIT_GROSS_PRICE => 888,
                        ItemTransfer::UNIT_NET_PRICE => 777,
                    ])
                    ->build(),
                777,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function throwsNullValueExceptionWhenRequiredTransferPropertyIsNotSetDataProvider(): array
    {
        return [
            'QuoteTransfer.priceMode is not set' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => null]))
                    ->withItem([
                        ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
                        ItemTransfer::UNIT_GROSS_PRICE => 888,
                        ItemTransfer::UNIT_NET_PRICE => 777,
                    ])
                    ->build(),
                'Property "priceMode" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'ItemTransfer.unitGrossPrice is not set when price mode is GROSS' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS]))
                    ->withItem([
                        ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
                        ItemTransfer::UNIT_GROSS_PRICE => null,
                        ItemTransfer::UNIT_NET_PRICE => 777,
                    ])
                    ->build(),
                'Property "unitGrossPrice" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
            'ItemTransfer.unitNetPrice is not set when price mode is NET' => [
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => static::PRICE_MODE_NET]))
                    ->withItem([
                        ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
                        ItemTransfer::UNIT_GROSS_PRICE => 888,
                        ItemTransfer::UNIT_NET_PRICE => null,
                    ])
                    ->build(),
                'Property "unitNetPrice" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\DiscountableItemTransfer> $collectedItems
     * @param string $sku
     *
     * @return bool
     */
    protected function isItemCollected(array $collectedItems, string $sku): bool
    {
        foreach ($collectedItems as $discountableItemTransfer) {
            if ($discountableItemTransfer->getOriginalItemOrFail()->getSkuOrFail() === $sku) {
                return true;
            }
        }

        return false;
    }
}
