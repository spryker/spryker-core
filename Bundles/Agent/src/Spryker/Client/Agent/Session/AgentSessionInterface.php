<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Session;

use Generated\Shared\Transfer\UserTransfer;

interface AgentSessionInterface
{
    public function isLoggedIn(): bool;

    public function getAgent(): ?UserTransfer;

    public function setAgent(UserTransfer $userTransfer): void;

    public function invalidateAgent(): void;
}
