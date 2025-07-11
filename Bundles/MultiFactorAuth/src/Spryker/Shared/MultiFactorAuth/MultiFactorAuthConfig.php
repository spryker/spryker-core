<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuth;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class MultiFactorAuthConfig extends AbstractSharedConfig
{
    /**
     * @var int
     */
    protected const CUSTOMER_CODE_LENGTH = 6;

    /**
     * @var int
     */
    protected const USER_CODE_LENGTH = 6;

    /**
     * Specification:
     * - Returns the multi-factor authentication code length for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerCodeLength(): int
    {
        return static::CUSTOMER_CODE_LENGTH;
    }

    /**
     * Specification:
     * - Returns the multi-factor authentication code length for user.
     *
     * @api
     *
     * @return int
     */
    public function getUserCodeLength(): int
    {
        return static::USER_CODE_LENGTH;
    }
}
