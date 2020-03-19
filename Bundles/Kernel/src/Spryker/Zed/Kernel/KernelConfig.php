<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

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
     * @return string[]
     */
    public function getProjectOrganizations(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCoreOrganizations(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * @api
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
     * @return string[]
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
     * @return string[]
     */
    public function getPathsToCoreModules(): array
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-shop/*/src/*/*/',
            APPLICATION_VENDOR_DIR . '/spryker-eco/*/src/*/*/',
        ];
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
     * @return string[]
     */
    public function getResolvableTypeClassNamePatternMap(): array
    {
        return $this->getSharedConfig()->getResolvableTypeClassNamePatternMap();
    }

    /**
     * @api
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
}
