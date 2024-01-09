<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var bool
     */
    protected const IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED = false;

    /**
     * Specification:
     * - Returns whether email update should be protected with password validation.
     *
     * @api
     *
     * @return bool
     */
    public function isEmailUpdatePasswordVerificationEnabled(): bool
    {
        return static::IS_EMAIL_UPDATE_PASSWORD_VERIFICATION_ENABLED;
    }
}
