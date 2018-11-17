<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business\Agent;

use Generated\Shared\Transfer\FindAgentResponseTransfer;

interface AgentReaderInterface
{
    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\FindAgentResponseTransfer
     */
    public function findAgentByUsername(string $username): FindAgentResponseTransfer;
}
