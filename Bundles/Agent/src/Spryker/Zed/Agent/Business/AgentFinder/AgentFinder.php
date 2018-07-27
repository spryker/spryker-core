<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business\AgentFinder;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Agent\Persistence\AgentRepositoryInterface;

class AgentFinder implements AgentFinderInterface
{
    /**
     * @var \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface
     */
    protected $agentRepository;

    /**
     * @param \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface $agentRepository
     */
    public function __construct(AgentRepositoryInterface $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(string $username): UserTransfer
    {
        return $this->getAgentRepository()
            ->findAgentByUsername($username);
    }

    /**
     * @return \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface
     */
    protected function getAgentRepository(): AgentRepositoryInterface
    {
        return $this->agentRepository;
    }
}
