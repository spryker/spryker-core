<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Agent\Business\AgentFacadeInterface;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\User\Business\UserFacade;
use Spryker\Zed\User\Business\UserFacadeInterface;

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
        $userTransfer = $this->registerAgent();

        $agentFromAgentFacade = $this->getAgentFacade()
            ->findAgentByUsername($userTransfer->getUsername());

        $this->assertNotEmpty($agentFromAgentFacade->getIdUser());
        $this->assertTrue($agentFromAgentFacade->getIsAgent());
    }

    /**
     * @return void
     */
    public function testGetNonExitingAgentByUsername(): void
    {
        $agentFromAgentFacade = $this->getAgentFacade()
            ->findAgentByUsername(
                $this->createAgent()->getUsername()
            );

        $this->assertEmpty($agentFromAgentFacade->getIdUser());
    }

    /**
     * @return void
     */
    public function testFindCustomersByQuery(): void
    {
        $customerTransfer = $this->registerCustomer();
        $customerQueryTransfer = new CustomerQueryTransfer();
        $customerQueryTransfer->setQuery($customerTransfer->getFirstName());

        $customerAutocompleteResponseTransfer = $this->getAgentFacade()
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

        $customerAutocompleteResponseTransfer = $this->getAgentFacade()
            ->findCustomersByQuery($customerQueryTransfer);

        $this->assertEquals(0, $customerAutocompleteResponseTransfer->getCustomers()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function registerAgent(): UserTransfer
    {
        return $this->getUserFacade()
            ->createUser($this->createAgent());
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function createAgent(): UserTransfer
    {
        $userTransfer = (new UserBuilder())->build();
        $userTransfer->setIsAgent(true);

        return $userTransfer;
    }

    /**
     * @return \Spryker\Zed\Agent\Business\AgentFacadeInterface
     */
    protected function getAgentFacade(): AgentFacadeInterface
    {
        /** @var \Spryker\Zed\Agent\Business\AgentFacadeInterface $facade */
        $facade = $this->tester->getFacade();

        return $facade;
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return new UserFacade();
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
