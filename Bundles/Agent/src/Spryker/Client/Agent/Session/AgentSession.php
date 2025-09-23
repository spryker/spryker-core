<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Session;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Client\Agent\Dependency\Client\AgentToSessionClientInterface;

class AgentSession implements AgentSessionInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_INVALIDATE_TIME = 1;

    /**
     * @var string
     */
    protected const SESSION_KEY = 'agent-session';

    public function __construct(protected AgentToSessionClientInterface $sessionClient)
    {
    }

    public function isLoggedIn(): bool
    {
        return $this->getSessionClient()->has(static::SESSION_KEY);
    }

    public function getAgent(): UserTransfer
    {
        return $this->getSessionClient()->get(static::SESSION_KEY);
    }

    public function setAgent(UserTransfer $userTransfer): void
    {
        $this->getSessionClient()->set(static::SESSION_KEY, $userTransfer);
    }

    public function invalidateAgent(): void
    {
        $this->getSessionClient()->invalidate(static::DEFAULT_INVALIDATE_TIME);
    }

    protected function getSessionClient(): AgentToSessionClientInterface
    {
        return $this->sessionClient;
    }
}
