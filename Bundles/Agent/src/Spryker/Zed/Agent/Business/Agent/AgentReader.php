<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business\Agent;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Agent\Persistence\AgentRepositoryInterface;

class AgentReader implements AgentReaderInterface
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
    public function findAgentByUsername(string $username): UserTransfer
    {
        return $this->agentRepository->findAgentByUsername($username);
    }
}
