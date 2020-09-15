<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAgentConnector\Dependency\Facade;

use Generated\Shared\Transfer\FindAgentResponseTransfer;

class OauthAgentConnectorToAgentFacadeBridge implements OauthAgentConnectorToAgentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Agent\Business\AgentFacadeInterface
     */
    protected $agentFacade;

    /**
     * @param \Spryker\Zed\Agent\Business\AgentFacadeInterface $agentFacade
     */
    public function __construct($agentFacade)
    {
        $this->agentFacade = $agentFacade;
    }

    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\FindAgentResponseTransfer
     */
    public function findAgentByUsername(string $username): FindAgentResponseTransfer
    {
        return $this->agentFacade->findAgentByUsername($username);
    }
}
