<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\MerchantDiscountConnector\MerchantDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantDiscountConnector
 * @group Business
 * @group Facade
 * @group IsMerchantReferenceSatisfiedByTest
 * Add your own group annotations below this line
 */
class IsMerchantReferenceSatisfiedByTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';

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
     * @dataProvider shouldReturnExpectedResultForItemAndClauseDataProvider
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $operator
     * @param string $merchantReference
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testShouldReturnExpectedResultForItemAndClause(
        ItemTransfer $itemTransfer,
        string $operator,
        string $merchantReference,
        bool $expectedResult
    ): void {
        // Arrange
        $clauseTransfer = $this->tester->createClauseTransfer($operator, $merchantReference);

        // Act
        $result = $this->tester->getFacade()->isMerchantReferenceSatisfiedBy(new QuoteTransfer(), $itemTransfer, $clauseTransfer);

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array<string, mixed>
     */
    protected function shouldReturnExpectedResultForItemAndClauseDataProvider(): array
    {
        return [
            'returns true when merchant reference matches the clause with "is in" operator' => [
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE]))->build(),
                static::IS_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE,
                true,
            ],
            'returns true when merchant reference matches the clause with "is not in" operator' => [
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => 'different-merchant-reference']))->build(),
                static::IS_NOT_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE,
                true,
            ],
            'returns false when merchant reference does not match the clause with "is in" operator' => [
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => 'different-merchant-reference']))->build(),
                static::IS_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE,
                false,
            ],
            'returns false when merchant reference does not match the clause with "is not in" operator' => [
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE]))->build(),
                static::IS_NOT_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE,
                false,
            ],
            'returns false when merchant reference is not set' => [
                (new ItemBuilder([ItemTransfer::MERCHANT_REFERENCE => null]))->build(),
                static::IS_NOT_IN_EXPRESSION,
                static::TEST_MERCHANT_REFERENCE,
                false,
            ],
        ];
    }
}
