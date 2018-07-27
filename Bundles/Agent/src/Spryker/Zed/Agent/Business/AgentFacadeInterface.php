<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business;

use Generated\Shared\Transfer\UserTransfer;

interface AgentFacadeInterface
{
    /**
     * Specification:
     * - Returns UserTransfer with an agent.
     * - If username is not exist, an empty transfer will be returned.
     *
     * @api
     *
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(string $username): UserTransfer;
}
