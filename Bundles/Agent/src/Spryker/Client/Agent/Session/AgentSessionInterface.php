<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Session;

use Generated\Shared\Transfer\UserTransfer;

interface AgentSessionInterface
{
    /**
     * @return bool
     */
    public function isLoggedIn(): bool;

    /**
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function getAgent(): ?UserTransfer;

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function setAgent(UserTransfer $userTransfer): void;
}
