<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication;

use Spryker\Shared\MerchantPortalApplication\MerchantPortalConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationConfig getSharedConfig()
 */
class MerchantPortalApplicationConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @var string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @var string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @var string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @var string
     */
    protected const HEADER_PERMISSIONS_POLICY_VALUE = '';

    /**
     * @var string
     */
    protected const HEADER_PERMISSION_POLICY_VALUE = '';

    /**
     * @uses \Spryker\Shared\Application\ApplicationConstants::ENABLE_APPLICATION_DEBUG
     *
     * @var string
     */
    protected const ENABLE_APPLICATION_DEBUG = 'ENABLE_APPLICATION_DEBUG';

    /**
     * Specification:
     * - Enables/disables global setting for merchant portal debug mode.
     * - Defaults to false.
     *
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return (bool)$this->get(MerchantPortalConstants::ENABLE_APPLICATION_DEBUG, false) || (bool)$this->get(static::ENABLE_APPLICATION_DEBUG, false);
    }

    /**
     * Specification:
     * - Returns array of security headers for server response.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getSecurityHeaders(): array
    {
        return [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Permissions-Policy' => static::HEADER_PERMISSION_POLICY_VALUE,
        ];
    }
}
