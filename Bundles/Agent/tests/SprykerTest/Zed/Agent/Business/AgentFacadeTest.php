<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Agent
 * @group Business
 * @group Facade
 * @group AgentFacadeTest
 * Add your own group annotations below this line
 */
class AgentFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_CUSTOMER_FIRST_NAME = 'customerFirstName';

    /**
     * @uses \Spryker\Zed\Agent\AgentConfig::DEFAULT_CUSTOMER_PAGINATION_LIMIT
     *
     * @var int
     */
    protected const DEFAULT_CUSTOMER_PAGINATION_LIMIT = 10;

    /**
     * @var \SprykerTest\Zed\Agent\AgentBusinessTester
     */
    protected $tester;

    /**
     * @var array<\Generated\Shared\Transfer\CustomerTransfer>
     */
    protected $customerTransfers;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customerTransfers = $this->tester->createCustomers();
    }

    /**
     * @return void
     */
    public function testGetExitingAgentByUsername(): void
    {
        // Arrange
        $userTransfer = $this->tester->registerAgent();

        // Act
        $agentFromAgentFacade = $this->tester->getFacade()
            ->findAgentByUsername($userTransfer->getUsername());

        // Assert
        $this->assertNotEmpty($agentFromAgentFacade->getAgent());
        $this->assertTrue($agentFromAgentFacade->getIsAgentFound());
    }

    /**
     * @return void
     */
    public function testGetNonExitingAgentByUsername(): void
    {
        // Act
        $agentFromAgentFacade = $this->tester->getFacade()
            ->findAgentByUsername(
                $this->tester->createAgent()->getUsername(),
            );

        // Assert
        $this->assertFalse($agentFromAgentFacade->getIsAgentFound());
    }

    /**
     * @return void
     */
    public function testFindCustomersByQuery(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery($customerTransfer->getFirstName());
        $customerQueryTransfer->setLimit(5);

        // Act
        $customerAutocompleteResponseTransfer = $this->tester->getFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        // Assert
        $this->assertGreaterThan(0, $customerAutocompleteResponseTransfer->getCustomers()->count());
    }

    /**
     * @return void
     */
    public function testFindNonExitingCustomersByQuery(): void
    {
        // Arrange
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery(uniqid('customer name', true));

        // Act
        $customerAutocompleteResponseTransfer = $this->tester->getFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        // Assert
        $this->assertCount(0, $customerAutocompleteResponseTransfer->getCustomers());
    }

    /**
     * @return void
     */
    public function testFindCustomersByQueryUsesDefaultLimitWhenNoLimitProvided(): void
    {
        // Arrange
        for ($i = 0; $i < static::DEFAULT_CUSTOMER_PAGINATION_LIMIT + 1; $i++) {
            $this->tester->haveCustomer([CustomerTransfer::FIRST_NAME => static::TEST_CUSTOMER_FIRST_NAME . $i]);
        }
        $customerQueryTransfer = (new CustomerQueryTransfer())->setQuery(static::TEST_CUSTOMER_FIRST_NAME);

        // Act
        $customerAutocompleteResponseTransfer = $this->tester->getFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        // Assert
        $this->assertSame(static::DEFAULT_CUSTOMER_PAGINATION_LIMIT, $customerAutocompleteResponseTransfer->getPagination()->getMaxPerPage());
        $this->assertCount(static::DEFAULT_CUSTOMER_PAGINATION_LIMIT, $customerAutocompleteResponseTransfer->getCustomers());
    }

    /**
     * @dataProvider findCustomersByQueryWithOffsetAndLimitRetrivesCustomersDataProvider
     *
     * @param \Generated\Shared\Transfer\CustomerQueryTransfer $customerQueryTransfer
     * @param int $expectedOffset
     *
     * @return void
     */
    public function testFindCustomersByQueryWithOffsetAndLimitRetrievesCustomers(
        CustomerQueryTransfer $customerQueryTransfer,
        int $expectedOffset
    ): void {
        // Act
        $customerAutocompleteResponseTransfer = $this->tester->getFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        // Assert
        $this->assertCount(
            $customerQueryTransfer->getLimit(),
            $customerAutocompleteResponseTransfer->getCustomers(),
            'Returned customers count should be equal to limit.',
        );
        foreach ($customerAutocompleteResponseTransfer->getCustomers() as $index => $actualCustomerTransfer) {
            $expectedCustomerTransfer = $this->customerTransfers[$expectedOffset + $index];
            $this->assertSame(
                $expectedCustomerTransfer->getCustomerReference(),
                $actualCustomerTransfer->getCustomerReference(),
                'Returned customers collection must have the correct offset.',
            );
        }
    }

    /**
     * @return array
     */
    public function findCustomersByQueryWithOffsetAndLimitRetrivesCustomersDataProvider(): array
    {
        return [
            [
                (new CustomerQueryTransfer())
                    ->setQuery(static::TEST_CUSTOMER_FIRST_NAME)
                    ->setLimit(10),
                0,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery(static::TEST_CUSTOMER_FIRST_NAME)
                    ->setLimit(5),
                0,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery(static::TEST_CUSTOMER_FIRST_NAME)
                    ->setLimit(5)
                    ->setOffset(5),
                5,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery(static::TEST_CUSTOMER_FIRST_NAME)
                    ->setLimit(3)
                    ->setOffset(5),
                3,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery(static::TEST_CUSTOMER_FIRST_NAME)
                    ->setLimit(3)
                    ->setOffset(6),
                6,
            ],
        ];
    }
}
