<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CustomerDataChangeRequest;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class CustomerDataChangeRequestConfig extends AbstractSharedConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_EMAIL_CHANGE_VERIFICATION_EXPIRATION_MINUTES = 30;

    /**
     * Specification:
     * - Returns the expiration time in minutes for the email change verification token.
     *
     * @api
     *
     * @return int
     */
    public function getEmailChangeVerificationExpirationMinutes(): int
    {
        return static::DEFAULT_EMAIL_CHANGE_VERIFICATION_EXPIRATION_MINUTES;
    }
}
