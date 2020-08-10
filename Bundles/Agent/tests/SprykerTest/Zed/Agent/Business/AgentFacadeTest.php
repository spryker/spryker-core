<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

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
     * @var \SprykerTest\Zed\Agent\AgentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetExitingAgentByUsername(): void
    {
        $userTransfer = $this->tester->registerAgent();

        $agentFromAgentFacade = $this->tester->getAgentFacade()
            ->findAgentByUsername($userTransfer->getUsername());

        $this->assertNotEmpty($agentFromAgentFacade->getAgent());
        $this->assertTrue($agentFromAgentFacade->getIsAgentFound());
    }

    /**
     * @return void
     */
    public function testGetNonExitingAgentByUsername(): void
    {
        $agentFromAgentFacade = $this->tester->getAgentFacade()
            ->findAgentByUsername(
                $this->tester->createAgent()->getUsername()
            );

        $this->assertFalse($agentFromAgentFacade->getIsAgentFound());
    }

    /**
     * @return void
     */
    public function testFindCustomersByQuery(): void
    {
        $customerTransfer = $this->tester->haveCustomer();
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery($customerTransfer->getFirstName());
        $customerQueryTransfer->setLimit(5);

        $customerAutocompleteResponseTransfer = $this->tester->getAgentFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        $this->assertGreaterThan(0, $customerAutocompleteResponseTransfer->getCustomers()->count());
    }

    /**
     * @return void
     */
    public function testFindNonExitingCustomersByQuery(): void
    {
        $customerTransfer = $this->createCustomer();
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery($customerTransfer->getFirstName());

        $customerAutocompleteResponseTransfer = $this->tester->getAgentFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        $this->assertEquals(0, $customerAutocompleteResponseTransfer->getCustomers()->count());
    }

    /**
     * @dataProvider findCustomersByQueryPagination
     *
     * @param \Generated\Shared\Transfer\CustomerQueryTransfer $customerQueryTransfer
     * @param int $count
     *
     * @return void
     */
    public function testFindCustomersByQueryOffsetLimit(
        CustomerQueryTransfer $customerQueryTransfer,
        int $count
    ): void {
        $this->createNCustomers();

        $customerAutocompleteResponseTransfer = $this->tester->getAgentFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        $this->assertEquals($count, $customerAutocompleteResponseTransfer->getCustomers()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomer(): CustomerTransfer
    {
        return (new CustomerBuilder(['firstName' => uniqid('', true)]))->build();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        return new CustomerFacade();
    }

    /**
     * @param int $count
     *
     * @return void
     */
    protected function createNCustomers(int $count = 10): void
    {
        while ($count) {
            $this->tester->haveCustomer();
            $count--;
        }
    }

    /**
     * @return array
     */
    public function findCustomersByQueryPagination(): array
    {
        return [
            [
                (new CustomerQueryTransfer())
                    ->setQuery('')
                    ->setLimit(10),
                10,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery('')
                    ->setLimit(5),
                5,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery('')
                    ->setLimit(5)
                    ->setOffset(5),
                5,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery('')
                    ->setLimit(3)
                    ->setOffset(5),
                3,
            ],
            [
                (new CustomerQueryTransfer())
                    ->setQuery('')
                    ->setLimit(3)
                    ->setOffset(6),
                3,
            ],
        ];
    }
}
