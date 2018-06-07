<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Module;

use Generated\Shared\Transfer\ModuleCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Spryker\Zed\SprykGui\Dependency\Client\SprykGuiToSessionClientInterface;
use Symfony\Component\Finder\Finder;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class ModuleFinder implements ModuleFinderInterface
{
    const MODULE_COLLECTION_TRANSFER_CACHE_KEY = 'moduleCollectionTransfer';

    /**
     * @var \Spryker\Zed\SprykGui\Dependency\Client\SprykGuiToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var array
     */
    protected $moduleDefinition = [
        'Spryker' => 'spryker/spryker/Bundles/*/src/',
        'SprykerEco' => 'spryker-eco/*/src/',
        'SprykerShop' => 'spryker/spryker-shop/Bundles/*/src/',
    ];

    /**
     * @var \Generated\Shared\Transfer\ModuleCollectionTransfer
     */
    protected $moduleCollectionTransfer;

    /**
     * @param \Spryker\Zed\SprykGui\Dependency\Client\SprykGuiToSessionClientInterface $sessionClient
     */
    public function __construct(SprykGuiToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleCollectionTransfer
     */
    public function findModules(): ModuleCollectionTransfer
    {
        if ($this->sessionClient->has(static::MODULE_COLLECTION_TRANSFER_CACHE_KEY)) {
            return $this->sessionClient->get(static::MODULE_COLLECTION_TRANSFER_CACHE_KEY);
        }

        $finder = new Finder();

        $directories = $this->buildDirectories();

        $finder->in($directories)->directories()->depth('== 0');

        $this->moduleCollectionTransfer = new ModuleCollectionTransfer();

        foreach ($finder as $fileInfo) {
            $moduleTransfer = new ModuleTransfer();
            $moduleName = $this->getModuleNameFromPath($fileInfo->getPath());
            $moduleRoot = $this->getModuleRootFromPath($fileInfo->getPath());
            $moduleTransfer->setName($moduleName);
            $moduleTransfer->setOrganization($fileInfo->getFilename());
            $moduleTransfer->setRootPath($moduleRoot);

            $this->moduleCollectionTransfer->addModule($moduleTransfer);
        }

        $this->sessionClient->set(static::MODULE_COLLECTION_TRANSFER_CACHE_KEY, $this->moduleCollectionTransfer);

        return $this->moduleCollectionTransfer;
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
