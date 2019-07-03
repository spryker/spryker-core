<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Application\ApplicationConfig getSharedConfig()
 */
class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @const string
     */
    protected const HEADER_X_FRAME_OPTIONS = 'X-Frame-Options';

    /**
     * @const string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY = 'Content-Security-Policy';

    /**
     * @const string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';

    /**
     * @const string
     */
    protected const HEADER_X_XSS_PROTECTION = 'X-XSS-Protection';

    /**
     * @const string
     */
    protected const HEADER_REFERRER_POLICY = 'Referrer-Policy';

    /**
     * @const string
     */
    protected const HEADER_FEATURE_POLICY = 'Feature-Policy';

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->get(ApplicationConstants::HOST_ZED);
    }

    /**
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->get(ApplicationConstants::ZED_SSL_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getSslExcludedResources()
    {
        return $this->get(ApplicationConstants::ZED_SSL_EXCLUDED, []);
    }

    /**
     * @return array
     */
    public function getTrustedProxies()
    {
        return $this->get(ApplicationConstants::ZED_TRUSTED_PROXIES, []);
    }

    /**
     * @return array
     */
    public function getTrustedHosts()
    {
        return $this->get(ApplicationConstants::ZED_TRUSTED_HOSTS, []);
    }

    /**
     * @return string
     */
    public function getProjectNamespace(): string
    {
        return $this->get(ApplicationConstants::PROJECT_NAMESPACE);
    }

    /**
     * @return string
     */
    public function getTwigEnvironmentName(): string
    {
        return $this->get(ApplicationConstants::TWIG_ENVIRONMENT_NAME, $this->getTwigEnvironmentNameDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getTwigEnvironmentNameDefaultValue(): string
    {
        return APPLICATION_ENV;
    }

    /**
     * @return bool
     */
    public function isPrettyErrorHandlerEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_PRETTY_ERROR_HANDLER, $this->getPrettyErrorHandlerDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getPrettyErrorHandlerDefaultValue(): bool
    {
        return APPLICATION_ENV === 'development';
    }

    /**
     * @return array
     */
    public function getSecurityHeaders(): array
    {
        return [
            static::HEADER_X_FRAME_OPTIONS => 'SAMEORIGIN',
            static::HEADER_CONTENT_SECURITY_POLICY => 'frame-ancestors \'self\'',
            static::HEADER_X_CONTENT_TYPE_OPTIONS => 'nosniff',
            static::HEADER_X_XSS_PROTECTION => '1; mode=block',
            static::HEADER_REFERRER_POLICY => 'same-origin',
            static::HEADER_FEATURE_POLICY => '',
        ];
    }
}
