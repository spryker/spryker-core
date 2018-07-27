<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\UserBuilder;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Agent\Business\AgentFacade;
use Spryker\Zed\Agent\Business\AgentFacadeInterface;
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
     * @return void
     */
    public function testGetExitingAgentByUsername(): void
    {
        $userTransfer = $this->registerAgent();

        $agentFromAgentFacade = $this->getAgentFacade()
            ->getAgentByUsername($userTransfer->getUsername());

        $this->assertNotEmpty($agentFromAgentFacade->getIdUser());
        $this->assertTrue($agentFromAgentFacade->getIsAgent());
    }

    /**
     * @return void
     */
    public function testGetNonExitingAgentByUsername(): void
    {
        $agentFromAgentFacade = $this->getAgentFacade()
            ->getAgentByUsername(
                $this->createAgent()->getUsername()
            );

        $this->assertEmpty($agentFromAgentFacade->getIdUser());
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
        return new AgentFacade();
    }

    /**
     * @return \Spryker\Zed\User\Business\UserFacadeInterface
     */
    protected function getUserFacade(): UserFacadeInterface
    {
        return new UserFacade();
    }
}
