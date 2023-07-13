<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent;

use Spryker\Zed\Kernel\AbstractBundleConfig;

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
     *
     * @return int
     */
    public function getDefaultCustomerPaginationLimit(): int
    {
        return static::DEFAULT_CUSTOMER_PAGINATION_LIMIT;
    }
}
