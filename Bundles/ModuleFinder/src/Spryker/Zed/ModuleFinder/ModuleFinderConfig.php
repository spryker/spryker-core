<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ModuleFinder;

use Spryker\Shared\ModuleFinder\ModuleFinderConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ModuleFinderConfig extends AbstractBundleConfig
{
    protected const NAMESPACE_SPRYKER = 'Spryker';
    protected const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';
    protected const NAMESPACE_SPRYKER_ECO = 'SprykerEco';
    protected const NAMESPACE_SPRYKER_SDK = 'SprykerSdk';
    protected const NAMESPACE_SPRYKER_MERCHANT_PORTAL = 'SprykerMerchantPortal';

    protected const APPLICATIONS = [
        'Client',
        'Service',
        'Shared',
        'Yves',
        'Zed',
        'Glue',
    ];

    protected const INTERNAL_NAMESPACES_LIST = [
        self::NAMESPACE_SPRYKER,
        self::NAMESPACE_SPRYKER_SHOP,
        self::NAMESPACE_SPRYKER_MERCHANT_PORTAL,
    ];

    protected const INTERNAL_NAMESPACES_TO_PATH_MAPPING = [
        self::NAMESPACE_SPRYKER => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/',
        self::NAMESPACE_SPRYKER_SHOP => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-shop/',
        self::NAMESPACE_SPRYKER_ECO => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-eco/',
        self::NAMESPACE_SPRYKER_SDK => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-sdk/',
        self::NAMESPACE_SPRYKER_MERCHANT_PORTAL => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-merchant-portal/',
    ];

    protected const INTERNAL_PACKAGE_DIRECTORIES = ['spryker', 'spryker-shop', 'spryker-merchant-portal'];

    /**
     * @return string[]
     */
    public function getInternalNamespacesList(): array
    {
        return static::INTERNAL_NAMESPACES_LIST;
    }

    /**
     * @return array
     */
    public function getApplications()
    {
        return static::APPLICATIONS;
    }

    /**
     * @return string[]
     */
    public function getInternalPackageDirectories(): array
    {
        return static::INTERNAL_PACKAGE_DIRECTORIES;
    }

    /**
     * @return string[]
     */
    public function getPathsToInternalNamespace(): array
    {
        $pathToSprykerRoot = $this->checkPathToSprykerRoot(static::NAMESPACE_SPRYKER);
        $sprykerNamespacePath = $pathToSprykerRoot ? [static::NAMESPACE_SPRYKER => $pathToSprykerRoot] : [];

        return $sprykerNamespacePath + static::INTERNAL_NAMESPACES_TO_PATH_MAPPING;
    }

    /**
     * @param string $namespace
     *
     * @return string|null
     */
    protected function checkPathToSprykerRoot(string $namespace): ?string
    {
        if ($namespace === static::NAMESPACE_SPRYKER) {
            $path = $this->getConfig()->get(ModuleFinderConstants::SPRYKER_ROOT);
            if ($path) {
                return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
        }

        return null;
    }
}
