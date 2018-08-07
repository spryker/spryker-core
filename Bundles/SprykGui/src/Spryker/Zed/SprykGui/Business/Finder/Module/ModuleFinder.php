<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Module;

use Generated\Shared\Transfer\ModuleCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var array
     */
    protected $moduleDefinition = [
        'Spryker' => 'spryker/spryker/Bundles/*/src/',
        'SprykerEco' => 'spryker-eco/*/src/',
        'SprykerShop' => 'spryker/spryker-shop/Bundles/*/src/',
    ];

    /**
     * @return \Generated\Shared\Transfer\ModuleCollectionTransfer
     */
    public function findModules(): ModuleCollectionTransfer
    {
        $finder = new Finder();

        $directories = $this->buildDirectories();

        $finder->in($directories)->directories()->depth('== 0');

        $moduleCollectionTransfer = new ModuleCollectionTransfer();

        foreach ($finder as $fileInfo) {
            $moduleName = $this->getModuleNameFromPath($fileInfo->getPath());
            $moduleRoot = $this->getModuleRootFromPath($fileInfo->getPath());

            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($fileInfo->getFilename())
                ->setRootPath($moduleRoot);

            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleName)
                ->setOrganization($organizationTransfer);

            $moduleCollectionTransfer->addModule($moduleTransfer);
        }

        return $moduleCollectionTransfer;
    }

    /**
     * @return array
     */
    protected function buildDirectories(): array
    {
        $directories = [];
        foreach ($this->moduleDefinition as $moduleOrganization => $path) {
            $directories[] = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR . $path;
        }

        return array_filter($directories, 'glob');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getModuleNameFromPath(string $path): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($pathFragments);

        $moduleName = array_pop($pathFragments);

        $filterChain = new FilterChain();
        $filterChain
            ->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($moduleName));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getModuleRootFromPath(string $path): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $path);
        array_pop($pathFragments);

        return implode(DIRECTORY_SEPARATOR, $pathFragments) . DIRECTORY_SEPARATOR;
    }
}
