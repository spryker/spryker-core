<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Zed;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class AgentStub implements AgentStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedStubClient;

    /**
     * @param \Spryker\Client\Agent\Dependency\Client\AgentToZedRequestClientInterface $zedStubClient
     */
    public function __construct(AgentToZedRequestClientInterface $zedStubClient)
    {
        $this->zedStubClient = $zedStubClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(UserTransfer $userTransfer): TransferInterface
    {
        return $this->zedStubClient->call('/agent/gateway/get-agent-by-username', $userTransfer);
    }
}
