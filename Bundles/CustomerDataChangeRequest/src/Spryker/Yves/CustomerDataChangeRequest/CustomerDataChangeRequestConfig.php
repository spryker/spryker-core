<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getSharedConfig()
 */
class CustomerDataChangeRequestConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the expiration time in minutes for the email change verification token.
     *
     * @api
     *
     * @uses \Spryker\Shared\CustomerDataChangeRequest\CustomerDataChangeRequestConfig::getEmailChangeVerificationExpirationMinutes()
     *
     * @return int
     */
    public function getEmailChangeVerificationExpirationMinutes(): int
    {
        return $this->getSharedConfig()->getEmailChangeVerificationExpirationMinutes();
    }
}
