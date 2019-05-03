<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class RouterConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_cache_class' => 'ZedUrlGenerator',
            'matcher_cache_class' => 'ZedUrlMatcher',
        ];
    }

    /**
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterEnvironmentConfigConstantsZed::IS_CACHE_ENABLED, true)) {
            return APPLICATION_ROOT_DIR . '/data/' . APPLICATION_STORE . '/cache/' . APPLICATION . '/routing';
        }

        return null;
    }

    /**
     * @return array
     */
    public function getControllerDirectories(): array
    {
        $controllerDirectories = [];

        foreach ($this->get(KernelConstants::PROJECT_NAMESPACES) as $projectNamespace) {
            $controllerDirectories[] = sprintf('%s/%s/Zed/*/Communication/Controller/', APPLICATION_SOURCE_DIR, $projectNamespace);
        }

        foreach ($this->get(KernelConstants::CORE_NAMESPACES) as $coreNamespace) {
            $controllerDirectories[] = sprintf('%s/spryker/*/src/%s/Zed/*/Communication/Controller/', APPLICATION_VENDOR_DIR, $coreNamespace);
        }

        return array_filter($controllerDirectories, 'glob');
    }

    /**
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(RouterEnvironmentConfigConstantsZed::IS_SSL_ENABLED, true);
    }

    /**
     * @return array
     */
    public function getSslExcludedRouteNames(): array
    {
        return $this->get(RouterEnvironmentConfigConstantsZed::SSL_EXCLUDED_ROUTE_NAMES, []);
    }
}
