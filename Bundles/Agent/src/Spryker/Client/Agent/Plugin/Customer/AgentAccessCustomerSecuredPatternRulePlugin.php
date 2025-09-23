<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Plugin\Customer;

use Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\Agent\AgentClient getClient()
 */
class AgentAccessCustomerSecuredPatternRulePlugin extends AbstractPlugin implements CustomerSecuredPatternRulePluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `true` if an agent is logged in, otherwise - `false`.
     *
     * @api
     */
    public function isApplicable(): bool
    {
        return $this->getClient()->isLoggedIn();
    }

    /**
     * {@inheritDoc}
     * - Reads a list of allowed patterns for an agent from the module's configuration.
     * - Modifies secured pattern based on a list.
     *
     * @api
     */
    public function execute(string $securedPattern): string
    {
        return $this->getClient()->applyAgentAccessOnSecuredPattern($securedPattern);
    }
}
