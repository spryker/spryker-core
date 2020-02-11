<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http;

use Spryker\Shared\Http\HttpConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Http\HttpConfig getSharedConfig()
 */
class HttpConfig extends AbstractBundleConfig
{
    protected const DEFAULT_REQUEST_HTTP_PORT = 80;
    protected const DEFAULT_REQUEST_HTTPS_PORT = 443;

    /**
     * @return int
     */
    public function getHttpPort(): int
    {
        return $this->get(HttpConstants::ZED_HTTP_PORT, static::DEFAULT_REQUEST_HTTP_PORT);
    }

    /**
     * @return int
     */
    public function getHttpsPort(): int
    {
        return $this->get(HttpConstants::ZED_HTTPS_PORT, static::DEFAULT_REQUEST_HTTPS_PORT);
    }

    /**
     * @return int
     */
    public function getTrustedHeaderSet(): int
    {
        return $this->getSharedConfig()->getTrustedHeaderSet();
    }

    /**
     * @return array
     */
    public function getTrustedProxies(): array
    {
        return $this->get(HttpConstants::ZED_TRUSTED_PROXIES, []);
    }

    /**
     * @return array
     */
    public function getTrustedHosts(): array
    {
        return $this->get(HttpConstants::ZED_TRUSTED_HOSTS, []);
    }

    /**
     * @return bool
     */
    public function isHstsEnabled(): bool
    {
        return $this->get(HttpConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED, false);
    }

    /**
     * @return array
     */
    public function getHstsConfig(): array
    {
        return $this->get(HttpConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG, []);
    }

    /**
     * @return string
     */
    public function getHttpFragmentPath(): string
    {
        return $this->getSharedConfig()->getHttpFragmentPath();
    }

    /**
     * @return string
     */
    public function getUriSignerSecret(): string
    {
        return $this->getSharedConfig()->getUriSignerSecret();
    }

    /**
     * @return string|null
     */
    public function getHIncludeRendererGlobalTemplate(): ?string
    {
        return $this->getSharedConfig()->getHIncludeRendererGlobalTemplate();
    }
}
