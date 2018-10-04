<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator;

use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class PackageVersionHydrator implements DependencyHydratorInterface
{
    public const NAME = 'name';
    public const VERSION = 'version';

    /**
     * @var array
     */
    private $installedPackages;

    /**
     * @param array $installedPackages
     */
    public function __construct(array $installedPackages)
    {
        $this->installedPackages = $installedPackages;
    }

    /**
     * @param array $dependency
     *
     * @return void
     */
    public function hydrate(array &$dependency)
    {
        $dependency[DependencyTree::META_COMPOSER_VERSION] = $this->getComposerVersion($dependency);
    }

    /**
     * @param array $dependency
     *
     * @return bool|string
     */
    private function getComposerVersion(array $dependency)
    {
        if ($dependency[DependencyTree::META_COMPOSER_NAME] === null || $dependency[DependencyTree::META_COMPOSER_NAME] === false) {
            return false;
        }

        foreach ($this->installedPackages as $installedPackage) {
            if ($installedPackage[static::NAME] === $dependency[DependencyTree::META_COMPOSER_NAME]) {
                return $installedPackage[static::VERSION];
            }
        }
    }
}
