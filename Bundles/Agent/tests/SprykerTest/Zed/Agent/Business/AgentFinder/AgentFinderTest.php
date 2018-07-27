<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent\Business\AgentFinder;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Agent\Business\AgentFinder\AgentFinder;
use Spryker\Zed\Agent\Business\AgentFinder\AgentFinderInterface;
use Spryker\Zed\Agent\Persistence\AgentRepositoryInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Agent
 * @group Business
 * @group AgentFinder
 * @group AgentFinderTest
 * Add your own group annotations below this line
 */
class AgentFinderTest extends Unit
{
    protected const EXITING_AGENT = 'agent@spryker.com';
    protected const NON_EXITING_AGENT = 'non-agent@spryker.com';

    /**
     * @return void
     */
    public function testGetExitingAgentByUsername(): void
    {
        $agentFinder = $this->createAgentFinder();
        $userTransfer = $agentFinder->getAgentByUsername(static::EXITING_AGENT);

        $this->assertNotEmpty($userTransfer->getIdUser());
    }

    /**
     * @return void
     */
    public function testGetNonExitingAgentByUsername(): void
    {
        $agentFinder = $this->createAgentFinder();
        $userTransfer = $agentFinder->getAgentByUsername(static::NON_EXITING_AGENT);

        $this->assertEmpty($userTransfer->getIdUser());
    }

    /**
     * @return \Spryker\Zed\Agent\Business\AgentFinder\AgentFinderInterface
     */
    protected function createAgentFinder(): AgentFinderInterface
    {
        return new AgentFinder(
            $this->createAgentRepositoryStub()
        );
    }

    /**
     * @return \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface
     */
    protected function createAgentRepositoryStub(): AgentRepositoryInterface
    {
        /** @var \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface $agentRepositoryStub */
        $agentRepositoryStub = Stub::makeEmpty(AgentRepositoryInterface::class, [
            'findAgentByUsername' => function (string $username): UserTransfer {
                $mockData = $this->getFindAgentByUsernameData()[$username];

                return (new UserTransfer())->fromArray($mockData);
            },
        ]);

        return $agentRepositoryStub;
    }

    /**
     * @return array
     */
    protected function getFindAgentByUsernameData(): array
    {
        return [
            static::EXITING_AGENT => [
                'id_user' => 1,
            ],
            static::NON_EXITING_AGENT => [],
        ];
    }
}
