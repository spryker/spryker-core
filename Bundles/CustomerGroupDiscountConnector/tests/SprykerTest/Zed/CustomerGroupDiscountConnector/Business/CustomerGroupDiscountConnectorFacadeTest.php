<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroupDiscountConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ClauseBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroupDiscountConnector
 * @group Business
 * @group Facade
 * @group CustomerGroupDiscountConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CustomerGroupDiscountConnectorFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const CUSTOMER_GROUP_NAME = 'Test Customer Group';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'customer-group';

    /**
     * @var array<string>
     */
    protected const ACCEPTED_TYPES = [
        'string',
        'list',
    ];

    /**
     * @var \SprykerTest\Zed\CustomerGroupDiscountConnector\CustomerGroupDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @dataProvider isCustomerGroupSatisfiedByShouldReturnCorrectResultAccordingToClauseDataProvider
     *
     * @param string $operator
     * @param string $value
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testIsCustomerGroupSatisfiedByShouldReturnCorrectResultAccordingToClause(
        string $operator,
        string $value,
        bool $expectedResult
    ): void {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $this->tester->haveCustomerGroup([
            CustomerGroupTransfer::NAME => static::CUSTOMER_GROUP_NAME,
            CustomerGroupTransfer::CUSTOMER_ASSIGNMENT => [
                CustomerGroupToCustomerAssignmentTransfer::IDS_CUSTOMER_TO_ASSIGN => [$customerTransfer->getIdCustomerOrFail()],
            ],
        ]);

        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->build();
        $quoteTransfer->setCustomer($customerTransfer);

        $clauseTransfer = (new ClauseBuilder([
            ClauseTransfer::ACCEPTED_TYPES => static::ACCEPTED_TYPES,
            ClauseTransfer::FIELD => static::FIELD_NAME,
            ClauseTransfer::OPERATOR => $operator,
            ClauseTransfer::VALUE => $value,
        ]))->build();

        // Act
        $result = $this->tester->getFacade()->isCustomerGroupSatisfiedBy(
            $quoteTransfer,
            $quoteTransfer->getItems()->offsetGet(0),
            $clauseTransfer,
        );

        // Assert
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array<array<string|bool>>
     */
    public function isCustomerGroupSatisfiedByShouldReturnCorrectResultAccordingToClauseDataProvider(): array
    {
        return [
            ['=', static::CUSTOMER_GROUP_NAME, true],
            ['=', strtolower(static::CUSTOMER_GROUP_NAME), true],
            ['=', strtoupper(static::CUSTOMER_GROUP_NAME), true],
            ['!=', 'Test', true],
            ['!=', static::CUSTOMER_GROUP_NAME, false],
            ['CONTAINS', 'Test', true],
            ['CONTAINS', 'Customer', true],
            ['CONTAINS', 'Group', true],
            ['CONTAINS', 'Test Customer', true],
            ['CONTAINS', 'Customer Group', true],
            ['CONTAINS', 'Test Group', false],
            ['DOES NOT CONTAIN', 'Test Group', true],
            ['DOES NOT CONTAIN', 'Test Customer', false],
        ];
    }
}
