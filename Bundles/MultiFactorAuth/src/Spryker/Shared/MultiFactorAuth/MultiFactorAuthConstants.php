<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MultiFactorAuth;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class MultiFactorAuthConstants
{
    /**
     * Specification:
     * - Defines the inactive status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const STATUS_INACTIVE = 0;

    /**
     * Specification:
     * - Defines the pending activation status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const STATUS_PENDING_ACTIVATION = 1;

    /**
     * Specification:
     * - Defines the active status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const STATUS_ACTIVE = 2;

    /**
     * Specification:
     * - Defines the unverified status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const CODE_UNVERIFIED = 0;

    /**
     * Specification:
     * - Defines the blocked status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const CODE_BLOCKED = 1;

    /**
     * Specification:
     * - Defines the verified status of the multi factor auth.
     *
     * @api
     *
     * @var int
     */
    public const CODE_VERIFIED = 2;
}
