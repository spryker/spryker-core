<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http;

use Spryker\Shared\Http\HttpConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Shared\Http\HttpConfig getSharedConfig()
 */
class HttpConfig extends AbstractBundleConfig
{
    protected const REQUEST_HTTP_PORT = 80;
    protected const REQUEST_HTTPS_PORT = 443;
    protected const REQUEST_TRUSTED_HEADER_SET = Request::HEADER_X_FORWARDED_ALL;
    protected const HTTP_FRAGMENT_PATH = '/_fragment';

    /**
     * @return int
     */
    public function getHttpPort(): int
    {
        return static::REQUEST_HTTP_PORT;
    }

    /**
     * @return int
     */
    public function getHttpsPort(): int
    {
        return static::REQUEST_HTTPS_PORT;
    }

    /**
     * @return int
     */
    public function getTrustedHeaderSet(): int
    {
        return static::REQUEST_TRUSTED_HEADER_SET;
    }

    /**
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(HttpConstants::YVES_SSL_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getSslExcludedResources(): array
    {
        return $this->get(HttpConstants::YVES_SSL_EXCLUDED, []);
    }

    /**
     * @return array
     */
    public function getTrustedProxies(): array
    {
        return $this->get(HttpConstants::YVES_TRUSTED_PROXIES, []);
    }

    /**
     * @return array
     */
    public function getTrustedHosts(): array
    {
        return $this->get(HttpConstants::YVES_TRUSTED_HOSTS, []);
    }

    /**
     * @return bool
     */
    public function isHstsEnabled(): bool
    {
        return $this->get(HttpConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED, false);
    }

    /**
     * @return array
     */
    public function getHstsConfig(): array
    {
        return $this->get(HttpConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_CONFIG, []);
    }

    /**
     * @return string
     */
    public function getHttpFragmentPath(): string
    {
        return static::HTTP_FRAGMENT_PATH;
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
        return null;
    }
}
