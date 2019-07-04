<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Request;

class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->get(ApplicationConstants::HOST_ZED);
    }

    /**
     * @return string
     */
    public function getYvesHostName(): string
    {
        return $this->get(ApplicationConstants::HOST_YVES);
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
     * @return int
     */
    public function getTrustedHeader(): int
    {
        return $this->get(ApplicationConstants::YVES_TRUSTED_HEADER, Request::HEADER_X_FORWARDED_ALL);
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
}
