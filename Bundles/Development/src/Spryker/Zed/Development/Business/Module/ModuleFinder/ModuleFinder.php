<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFinder;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var string[]
     */
    protected $moduleDirectories;

    /**
     * @param string[] $moduleDirectories
     */
    public function __construct(array $moduleDirectories)
    {
        $this->moduleDirectories = array_filter($moduleDirectories, 'is_dir');
    }

    /**
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function find(): array
    {
        $moduleTransferCollection = [];

        $moduleTransferCollection = $this->addStandAloneModulesToCollection($moduleTransferCollection);
        $moduleTransferCollection = $this->addModulesToCollection($moduleTransferCollection);

        return $moduleTransferCollection;
    }

    /**
     * @param array $moduleTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addStandAloneModulesToCollection(array $moduleTransferCollection): array
    {
        $standAloneModuleDirectories = (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/');

        foreach ($standAloneModuleDirectories as $directoryInfo) {
            if (in_array($directoryInfo->getFilename(), ['spryker', 'spryker-shop'])) {
                continue;
            }
            $moduleTransferCollection = $this->addModuleTransfer($moduleTransferCollection, $directoryInfo);
        }

        return $moduleTransferCollection;
    }

    /**
     * @param array $moduleTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addModulesToCollection(array $moduleTransferCollection): array
    {
        $moduleDirectories = (new Finder())->directories()->depth('== 0')->in($this->moduleDirectories);

        foreach ($moduleDirectories as $directoryInfo) {
            $moduleTransferCollection = $this->addModuleTransfer($moduleTransferCollection, $directoryInfo);
        }

        return $moduleTransferCollection;
    }

    /**
     * @param array $moduleTransferCollection
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addModuleTransfer(array $moduleTransferCollection, SplFileInfo $directoryInfo): array
    {
        $composerJsonAsArray = $this->getComposerJsonAsArray($directoryInfo);
        $organizationName = $this->getOrganizationNameFromComposer($composerJsonAsArray);

        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName($this->camelCase($organizationName))
            ->setNameDashed($organizationName);

        $moduleName = $this->getModuleNameFromComposer($composerJsonAsArray);
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($this->camelCase($moduleName))
            ->setNameDashed($moduleName)
            ->setRootDirectory($directoryInfo->getRealPath());

        $moduleTransfer->setOrganization($organizationTransfer);

        $moduleTransferCollection[$this->camelCase($moduleName)] = $moduleTransfer;

        return $moduleTransferCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return array
     */
    protected function getComposerJsonAsArray(SplFileInfo $directoryInfo): array
    {
        $pathToComposerJson = sprintf('%s/composer.json', $directoryInfo->getPathname());
        $fileContent = file_get_contents($pathToComposerJson);
        $composerJsonAsArray = json_decode($fileContent, true);

        return $composerJsonAsArray;
    }

    /**
     * @param array $composerJsonAsArray
     *
     * @return string
     */
    protected function getOrganizationNameFromComposer(array $composerJsonAsArray): string
    {
        $nameFragments = explode('/', $composerJsonAsArray['name']);
        $organizationName = $nameFragments[0];

        return $organizationName;
    }

    /**
     * @param array $composerJsonAsArray
     *
     * @return string
     */
    protected function getModuleNameFromComposer(array $composerJsonAsArray): string
    {
        $nameFragments = explode('/', $composerJsonAsArray['name']);
        $moduleName = $nameFragments[1];

        return $moduleName;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function camelCase(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return ucfirst($filterChain->filter($value));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function dasherize(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($value);
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function collectCoreModules()
    {
        $modules = (new Finder())->directories()->depth('== 0')->in($this->moduleDirectories);

        return $modules;
    }
}
