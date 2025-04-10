<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MultiFactorAuthConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const CUSTOMER_CODE_LENGTH = 6;

    /**
     * @var int
     */
    protected const CUSTOMER_CODE_VALIDITY_TTL = 10;

    /**
     * @var int
     */
    protected const CUSTOMER_ATTEMPTS_LIMIT = 3;

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
     * - Returns the code validity TTL in minutes for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerCodeValidityTtl(): int
    {
        return static::CUSTOMER_CODE_VALIDITY_TTL;
    }

    /**
     * Specification:
     * - Returns the multi-factor authentication code validation attempt limit for customer.
     *
     * @api
     *
     * @return int
     */
    public function getCustomerAttemptsLimit(): int
    {
        return static::CUSTOMER_ATTEMPTS_LIMIT;
    }
}
