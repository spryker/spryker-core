<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use FilesystemIterator;
use SplFileInfo;
use Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderConfigInterface;
use Spryker\Shared\Kernel\KernelConstants;

/**
 * @method \Spryker\Shared\Kernel\KernelConfig getSharedConfig()
 */
class KernelConfig extends AbstractBundleConfig implements ClassNameCandidatesBuilderConfigInterface
{
    /**
     * @api
     *
     * @return array<string>
     */
    public function getProjectOrganizations(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCoreOrganizations(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getCurrentStoreName(): string
    {
        return APPLICATION_STORE;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPathsToProjectModules(): array
    {
        $pathsToProjectModules = [];

        foreach ($this->getProjectOrganizations() as $projectOrganization) {
            $pathsToProjectModules[] = sprintf('%s/src/%s/*/', APPLICATION_ROOT_DIR, $projectOrganization);
        }

        return $pathsToProjectModules;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPathsToCoreModules(): array
    {
        $pathsToCoreModules = [
            APPLICATION_VENDOR_DIR . '/spryker/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-shop/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-eco/*/src/*/*/',
        ];

        if ($this->featureExists()) {
            $pathsToCoreModules[] = APPLICATION_VENDOR_DIR . '/spryker-feature/*/src/*/*/';
        }

        return $pathsToCoreModules;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function featureExists(): bool
    {
        $featuresDir = APPLICATION_VENDOR_DIR . '/spryker-feature/';

        if (is_dir($featuresDir)) {
            $iterator = new FilesystemIterator($featuresDir, FilesystemIterator::SKIP_DOTS);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo instanceof SplFileInfo && $fileinfo->isDir()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Key consist of:
     * - the module name placeholder (%s)
     * - the application name
     * - the layer (if Zed factory)
     * - the resolvable type
     *
     * Value contains placeholders for (ordered):
     * - the organization name
     * - the module candidate name (module, module + store, module + bucket, etc)
     * - the module name
     *
     * @api
     *
     * @return array<string>
     */
    public function getResolvableTypeClassNamePatternMap(): array
    {
        return $this->getSharedConfig()->getResolvableTypeClassNamePatternMap();
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Kernel\KernelConfig::getResolvableCacheFilePathPattern()} instead.
     *
     * @return string
     */
    public function getResolvableCacheFilePath(): string
    {
        return $this->getSharedConfig()->getResolvableCacheFilePath();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(KernelConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResolvableCacheFilePathPattern(): string
    {
        return $this->getSharedConfig()->getResolvableCacheFilePathPattern();
    }

    /**
     * Specification:
     * - Checks if strict domain redirect is enabled.
     * - When enabled, only the domains from the list returned by {@link \Spryker\Zed\Kernel\KernelConfig::getDomainsAllowedForRedirect()} are allowed for redirects.
     *
     * @api
     *
     * @return bool
     */
    public function isStrictDomainRedirectEnabled(): bool
    {
        return $this->getSharedConfig()->isStrictDomainRedirectEnabled();
    }

    /**
     * Specification:
     * - Gets the list of domains/subdomains allowed for redirects.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDomainsAllowedForRedirect(): array
    {
        return $this->getSharedConfig()->getDomainsAllowedForRedirect();
    }
}
