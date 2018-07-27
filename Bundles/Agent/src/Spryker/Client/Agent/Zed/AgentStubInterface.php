<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Zed;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AgentStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\UserTransfer
     */
    public function getAgentByUsername(UserTransfer $userTransfer): TransferInterface;
}
