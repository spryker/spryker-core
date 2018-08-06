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
        $customerTransfer = $this->registerCustomer();
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery($customerTransfer->getFirstName());

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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function registerCustomer(): CustomerTransfer
    {
        return $this->getCustomerFacade()
            ->registerCustomer($this->createCustomer())
            ->getCustomerTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomer(): CustomerTransfer
    {
        return (new CustomerBuilder())->build();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected function getCustomerFacade(): CustomerFacadeInterface
    {
        return new CustomerFacade();
    }
}
