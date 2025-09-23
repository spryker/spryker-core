<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Agent\AgentConfig getSharedConfig()
 */
class AgentConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_CUSTOMER_PAGINATION_LIMIT = 10;

    /**
     * Specification:
     * - Defines default limit for fetching customers.
     *
     * @api
     */
    public function getDefaultCustomerPaginationLimit(): int
    {
        return static::DEFAULT_CUSTOMER_PAGINATION_LIMIT;
    }

    /**
     * Specification:
     * - Enable or disable agent info capturing in the orders when agent assists with order placing.
     *
     * @api
     */
    public function isSalesOrderAgentEnabled(): bool
    {
        return $this->getSharedConfig()->isSalesOrderAgentEnabled();
    }
}
