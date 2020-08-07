<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Shared\Application\ApplicationConfig getSharedConfig()
 */
class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @const string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @const string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @const string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @const string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @const string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @const string
     */
    protected const HEADER_FEATURE_POLICY_VALUE = '';

    /**
     * @api
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->get(ApplicationConstants::YVES_SSL_ENABLED, true);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getSslExcludedResources()
    {
        return $this->get(ApplicationConstants::YVES_SSL_EXCLUDED, []);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTrustedProxies()
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_PROXIES, []);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTrustedHeader(): int
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_HEADER, Request::HEADER_X_FORWARDED_ALL);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTrustedHosts()
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_HOSTS, []);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getSecurityHeaders(): array
    {
        return [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Feature-Policy' => static::HEADER_FEATURE_POLICY_VALUE,
        ];
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }
}
