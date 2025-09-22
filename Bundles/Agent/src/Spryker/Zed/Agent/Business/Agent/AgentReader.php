<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business\Agent;

use Generated\Shared\Transfer\FindAgentResponseTransfer;
use Spryker\Zed\Agent\Persistence\AgentRepositoryInterface;

class AgentReader implements AgentReaderInterface
{
    public function __construct(protected AgentRepositoryInterface $agentRepository)
    {
    }

    public function findAgentByUsername(string $username): FindAgentResponseTransfer
    {
        $userTransfer = $this->agentRepository->findAgentByUsername($username);
        $findAgentResponseTransfer = new FindAgentResponseTransfer();

        $findAgentResponseTransfer->setIsAgentFound($userTransfer !== null);

        if ($findAgentResponseTransfer->getIsAgentFound()) {
            $findAgentResponseTransfer->setAgent($userTransfer);
        }

        return $findAgentResponseTransfer;
    }
}
