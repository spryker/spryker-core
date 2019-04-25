<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router;

use Spryker\Shared\Router\RouterConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;
use Spryker\Yves\Router\Generator\UrlGenerator;
use Spryker\Yves\Router\UrlMatcher\RedirectableUrlMatcher;

class RouterConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => RedirectableUrlMatcher::class,
            'matcher_base_class' => RedirectableUrlMatcher::class,
        ];
    }

    /**
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterConstants::ROUTER_CACHE_ENABLED_YVES, true)) {
            return APPLICATION_ROOT_DIR . '/data/' . APPLICATION_STORE . '/cache/' . APPLICATION . '/routing';
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(RouterConstants::ROUTER_IS_SSL_ENABLED_YVES, true);
    }

    /**
     * @return string[]
     */
    public function getSslExcludedRouteNames(): array
    {
        return $this->get(RouterConstants::ROUTER_SSL_EXCLUDED_ROUTE_NAMES_YVES, []);
    }

    /**
     * @return string[]
     */
    public function getAllowedLanguages(): array
    {
        return [
            'de',
            'en',
        ];
    }

    /**
     * @return string[]
     */
    public function getAllowedStores(): array
    {
        return [
            'DE',
            'US',
        ];
    }
}
