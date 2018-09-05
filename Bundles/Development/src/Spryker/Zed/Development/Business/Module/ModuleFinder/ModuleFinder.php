<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFinder;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
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
     * @var \Generated\Shared\Transfer\ModuleTransfer[]|null
     */
    protected static $moduleTransferCollection;

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
        if (static::$moduleTransferCollection === null) {
            $moduleTransferCollection = [];

            $moduleTransferCollection = $this->addStandaloneModulesToCollection($moduleTransferCollection);
            $moduleTransferCollection = $this->addModulesToCollection($moduleTransferCollection);

            ksort($moduleTransferCollection);

            static::$moduleTransferCollection = $moduleTransferCollection;
        }

        return static::$moduleTransferCollection;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getProjectModuleFinder(): Finder
    {
        $projectOrganizations = Config::get(KernelConstants::PROJECT_NAMESPACES);
        $projectOrganizationModuleDirectories = [];
        foreach ($projectOrganizations as $projectOrganization) {
            $projectOrganizationModuleDirectories[] = APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . $projectOrganization;
        }

        return (new Finder())->directories()->depth('== 0')->in($projectOrganizationModuleDirectories);
    }

    /**
     * @param array $moduleTransferCollection
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addStandaloneModulesToCollection(array $moduleTransferCollection): array
    {
        $standAloneModuleDirectories = (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/');

        foreach ($standAloneModuleDirectories as $directoryInfo) {
            if (in_array($directoryInfo->getFilename(), ['spryker', 'spryker-shop'])) {
                continue;
            }
            $moduleTransfer = $this->getModuleTransfer($directoryInfo);
            $moduleTransfer->setIsStandalone(true);

            $moduleTransferCollection[$this->buildCollectionKey($moduleTransfer)] = $moduleTransfer;
            $moduleTransferCollection[$moduleTransfer->getName()][] = $moduleTransfer;
        }

        return $moduleTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function buildCollectionKey(ModuleTransfer $moduleTransfer): string
    {
        return sprintf('%s.%s', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName());
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
            $moduleTransfer = $this->getModuleTransfer($directoryInfo);

            $moduleTransferCollection[$this->buildCollectionKey($moduleTransfer)] = $moduleTransfer;
            $moduleTransferCollection[$moduleTransfer->getName()][] = $moduleTransfer;
        }

        return $moduleTransferCollection;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(SplFileInfo $directoryInfo): ModuleTransfer
    {
        if ($this->existComposerJson($directoryInfo)) {
            return $this->buildModuleTransferFromComposerJsonInformation($directoryInfo);
        }

        return $this->buildModuleTransferFromDirectoryInformation($directoryInfo);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return bool
     */
    protected function existComposerJson(SplFileInfo $directoryInfo): bool
    {
        $pathToComposerJson = sprintf('%s/composer.json', $directoryInfo->getPathname());

        return file_exists($pathToComposerJson);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromDirectoryInformation(SplFileInfo $directoryInfo): ModuleTransfer
    {
        $organizationNameDashed = $this->getOrganizationNameFromDirectory($directoryInfo);
        $organizationName = $this->camelCase($organizationNameDashed);

        $moduleName = $this->camelCase($this->getModuleNameFromDirectory($directoryInfo));
        $moduleNameDashed = $this->dasherize($moduleName);

        $organizationTransfer = $this->buildOrganizationTransfer($organizationName, $organizationNameDashed);
        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer->setOrganization($organizationTransfer);

        return $moduleTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromComposerJsonInformation(SplFileInfo $directoryInfo): ModuleTransfer
    {
        $composerJsonAsArray = $this->getComposerJsonAsArray($directoryInfo);

        $organizationNameDashed = $this->getOrganizationNameFromComposer($composerJsonAsArray);
        $organizationName = $this->camelCase($organizationNameDashed);

        $moduleNameDashed = $this->getModuleNameFromComposer($composerJsonAsArray);
        $moduleName = $this->camelCase($moduleNameDashed);

        $organizationTransfer = $this->buildOrganizationTransfer($organizationName, $organizationNameDashed);
        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer->setOrganization($organizationTransfer);

        return $moduleTransfer;
    }

    /**
     * @param string $organizationName
     * @param string $organizationNameDashed
     *
     * @return \Generated\Shared\Transfer\OrganizationTransfer
     */
    protected function buildOrganizationTransfer(string $organizationName, string $organizationNameDashed): OrganizationTransfer
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName($organizationName)
            ->setNameDashed($organizationNameDashed);

        return $organizationTransfer;
    }

    /**
     * @param string $moduleName
     * @param string $moduleNameDashed
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransfer(string $moduleName, string $moduleNameDashed, SplFileInfo $directoryInfo): ModuleTransfer
    {
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setNameDashed($moduleNameDashed)
            ->setPath($directoryInfo->getRealPath())
            ->setIsStandalone(false);

        return $moduleTransfer;
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
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return string
     */
    protected function getOrganizationNameFromDirectory(SplFileInfo $directoryInfo): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $directoryInfo->getRealPath());
        $vendorPosition = array_search('vendor', $pathFragments);

        $organizationName = $pathFragments[$vendorPosition + 1];

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
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return string
     */
    protected function getModuleNameFromDirectory(SplFileInfo $directoryInfo): string
    {
        return $directoryInfo->getRelativePathname();
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
