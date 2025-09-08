<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Agent;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class AgentConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Enable or disable agent info saving in the orders when agent assists.
     * - Adds `spy_sales_order.agent_email` column in the DB.
     *
     * @api
     *
     * @return bool
     */
    public function isSalesOrderAgentEnabled(): bool
    {
        return false;
    }
}
