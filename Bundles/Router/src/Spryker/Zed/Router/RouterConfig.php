<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Router\Business\Generator\UrlGenerator;
use Spryker\Zed\Router\Business\UrlMatcher\CompiledUrlMatcher;

/**
 * @method \Spryker\Shared\Router\RouterConfig getSharedConfig()
 */
class RouterConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a Router configuration which makes use of a Router cache.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array
     */
    public function getBackofficeRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getBackofficeCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * @return string|null
     */
    protected function getBackofficeCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterConstants::BACKOFFICE_IS_CACHE_ENABLED, true)) {
            return $this->get(RouterConstants::BACKOFFICE_CACHE_PATH, $this->getBackofficeRouterCachePath());
        }

        return null;
    }

    /**
     * Specification:
     * - Defines the default path to the Router cache files.
     * - Can be redefined on Yves or Zed configs.
     *
     * @api
     *
     * @return string
     */
    public function getBackofficeRouterCachePath(): string
    {
        return sprintf(
            '%s/src/Generated/Router/Backoffice/codeBucket%s/',
            APPLICATION_ROOT_DIR,
            APPLICATION_CODE_BUCKET
        );
    }

    /**
     * Specification:
     * - Returns a Merchant Portal Router configuration which makes use of a Router cache.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array
     */
    public function getMerchantPortalRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getMerchantPortalRouterCachePath(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * Specification:
     * - Defines the default path to the MerchantPortal Router cache files.
     *
     * @api
     *
     * @return string
     */
    public function getMerchantPortalRouterCachePath(): string
    {
        return sprintf(
            '%s/src/Generated/Router/MerchantPortal/codeBucket%s/',
            APPLICATION_ROOT_DIR,
            APPLICATION_CODE_BUCKET
        );
    }

    /**
     * Specification:
     * - Returns a Router configuration which makes use of a Router cache.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array
     */
    public function getBackendGatewayRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getBackendGatewayCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * @return string|null
     */
    protected function getBackendGatewayCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterConstants::BACKEND_GATEWAY_IS_CACHE_ENABLED, true)) {
            return $this->get(RouterConstants::BACKEND_GATEWAY_CACHE_PATH, $this->getBackendGatewayRouterCachePath());
        }

        return null;
    }

    /**
     * Specification:
     * - Defines the default path to the Router cache files.
     * - Can be redefined on Yves or Zed configs.
     *
     * @api
     *
     * @return string
     */
    public function getBackendGatewayRouterCachePath(): string
    {
        return sprintf(
            '%s/src/Generated/Router/BackendGateway/codeBucket%s/',
            APPLICATION_ROOT_DIR,
            APPLICATION_CODE_BUCKET
        );
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Router\RouterConfig::getBackofficeRouterConfiguration()} instead.
     *
     * Specification:
     * - Returns a Router configuration which makes use of a Router cache.
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array
     */
    public function getRouterConfiguration(): array
    {
        return [
            'cache_dir' => $this->getCachePathIfCacheEnabled(),
            'generator_class' => UrlGenerator::class,
            'matcher_class' => CompiledUrlMatcher::class,
        ];
    }

    /**
     * Specification:
     * - Returns a Router configuration which does not make use of a Router cache.
     * - Fallback for development which is executed when the cached Router is not able to match.
     *
     * @api
     *
     * @see \Symfony\Component\Routing\Router::setOptions()
     *
     * @return array
     */
    public function getDevelopmentRouterConfiguration(): array
    {
        $routerConfiguration = $this->getRouterConfiguration();
        $routerConfiguration['cache_dir'] = null;

        return $routerConfiguration;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Router\RouterConfig::getBackofficeCachePathIfCacheEnabled()} instead.
     *
     * @return string|null
     */
    protected function getCachePathIfCacheEnabled(): ?string
    {
        if ($this->get(RouterConstants::ZED_IS_CACHE_ENABLED, true)) {
            return $this->get(RouterConstants::ZED_CACHE_PATH, $this->getSharedConfig()->getDefaultRouterCachePath());
        }

        return null;
    }

    /**
     * Specification:
     * - Returns an array of directories where Controller are placed.
     * - Used to build the Router cache.
     *
     * @api
     *
     * @return string[]
     */
    public function getControllerDirectories(): array
    {
        $controllerDirectories = [];
        $srcDirectory = $this->getSourceDirectory();
        $vendorDirectory = $this->getVendorDirectory();

        foreach ($this->get(KernelConstants::PROJECT_NAMESPACES) as $projectNamespace) {
            $controllerDirectories[] = sprintf('%s/%s/Zed/*/Communication/Controller/', $srcDirectory, $projectNamespace);
        }

        foreach ($this->get(KernelConstants::CORE_NAMESPACES) as $coreNamespace) {
            $composerPackageNamespace = strtolower(preg_replace('/([a-z0-9]|[A-Z0-9])([A-Z0-9])/', '$1-$2', $coreNamespace));

            $controllerDirectories[] = sprintf(
                '%s/%s/*/src/%s/Zed/*/Communication/Controller/',
                $vendorDirectory,
                $composerPackageNamespace,
                $coreNamespace
            );
        }

        return $this->filterDirectories($controllerDirectories);
    }

    /**
     * @return string
     */
    protected function getSourceDirectory(): string
    {
        return rtrim(APPLICATION_SOURCE_DIR, '/');
    }

    /**
     * @return string
     */
    protected function getVendorDirectory(): string
    {
        return rtrim(APPLICATION_VENDOR_DIR, '/');
    }

    /**
     * @param array $directories
     *
     * @return array
     */
    protected function filterDirectories(array $directories): array
    {
        return array_filter($directories, 'glob');
    }

    /**
     * Specification:
     * - Returns if the SSl is enabled.
     * - When it is enabled and the current request is not secure, the Router will redirect to a secured URL.
     *
     * @api
     *
     * @return bool
     */
    public function isSslEnabled(): bool
    {
        return $this->get(RouterConstants::ZED_IS_SSL_ENABLED, true);
    }

    /**
     * Specification:
     * - Returns SSl excluded Route names.
     * - When SSL is enabled and the current Route name is excluded, the Router will not redirect to a secured URL.
     *
     * @api
     *
     * @return string[]
     */
    public function getSslExcludedRouteNames(): array
    {
        return $this->get(RouterConstants::ZED_SSL_EXCLUDED_ROUTE_NAMES, []);
    }
}
