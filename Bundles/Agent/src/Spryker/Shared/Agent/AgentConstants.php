<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Agent;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface AgentConstants
{
    /**
     * Specification:
     * - Defines a list of secured patterns that are allowed for an agent.
     *
     * @api
     * @var string
     */
    public const AGENT_ALLOWED_SECURED_PATTERN_LIST = 'AGENT:AGENT_ALLOWED_SECURED_PATTERN_LIST';
}
