<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ModuleFinder;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
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
     * @var \Generated\Shared\Transfer\ModuleTransfer[]
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
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    public function getModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        if ($moduleFilterTransfer === null && static::$moduleTransferCollection !== null) {
            return static::$moduleTransferCollection;
        }

        $moduleTransferCollection = [];

        $moduleTransferCollection = $this->addStandaloneModulesToCollection($moduleTransferCollection, $moduleFilterTransfer);
        $moduleTransferCollection = $this->addModulesToCollection($moduleTransferCollection, $moduleFilterTransfer);

        ksort($moduleTransferCollection);

        if ($moduleFilterTransfer === null) {
            static::$moduleTransferCollection = $moduleTransferCollection;
        }

        return $moduleTransferCollection;
    }

    /**
     * @param array $moduleTransferCollection
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addStandaloneModulesToCollection(array $moduleTransferCollection, ?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        foreach ($this->getStandaloneModuleFinder() as $directoryInfo) {
            if (in_array($directoryInfo->getFilename(), ['spryker', 'spryker-shop'])) {
                continue;
            }
            $moduleTransfer = $this->getModuleTransfer($directoryInfo);
            $moduleTransfer->setIsStandalone(true);

            if (!$this->isModule($moduleTransfer)) {
                continue;
            }

            $moduleTransferCollection = $this->addModuleToCollection($moduleTransfer, $moduleTransferCollection, $moduleFilterTransfer);
        }

        return $moduleTransferCollection;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getStandaloneModuleFinder(): Finder
    {
        return (new Finder())->directories()->depth('== 0')->in(APPLICATION_VENDOR_DIR . '/spryker/');
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ModuleTransfer[] $moduleTransferCollection
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addModuleToCollection(ModuleTransfer $moduleTransfer, array $moduleTransferCollection, ?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        if ($moduleFilterTransfer !== null && !$this->matches($moduleTransfer, $moduleFilterTransfer)) {
            return $moduleTransferCollection;
        }

        $moduleTransferCollection[$this->buildCollectionKey($moduleTransfer)] = $moduleTransfer;

        return $moduleTransferCollection;
    }

    /**
     * Modules which are standalone, can also be normal modules. This can be detected by the composer.json description
     * which contains `module` at the end of the description.
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    protected function isModule(ModuleTransfer $moduleTransfer): bool
    {
        $composerJsonAsArray = $this->getComposerJsonAsArray($moduleTransfer->getPath());

        if (!isset($composerJsonAsArray['description'])) {
            return false;
        }

        $description = $composerJsonAsArray['description'];

        return preg_match('/\smodule$/', $description);
    }

    /**
     * @param array $moduleTransferCollection
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function addModulesToCollection(array $moduleTransferCollection, ?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        foreach ($this->getModuleFinder() as $directoryInfo) {
            $moduleTransfer = $this->getModuleTransfer($directoryInfo);
            $moduleTransferCollection = $this->addModuleToCollection($moduleTransfer, $moduleTransferCollection, $moduleFilterTransfer);
        }

        return $moduleTransferCollection;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getModuleFinder(): Finder
    {
        return (new Finder())->directories()->depth('== 0')->in($this->moduleDirectories);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(SplFileInfo $directoryInfo): ModuleTransfer
    {
        if ($this->existComposerJson($directoryInfo->getPathname())) {
            return $this->buildModuleTransferFromComposerJsonInformation($directoryInfo);
        }

        return $this->buildModuleTransferFromDirectoryInformation($directoryInfo);
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
     * @param string $path
     *
     * @return bool
     */
    protected function existComposerJson(string $path): bool
    {
        $pathToComposerJson = sprintf('%s/composer.json', $path);

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
        $applicationTransfer = $this->buildApplicationTransfer($directoryInfo);

        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer
            ->setOrganization($organizationTransfer)
            ->setApplication($applicationTransfer);

        return $moduleTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromComposerJsonInformation(SplFileInfo $directoryInfo): ModuleTransfer
    {
        $composerJsonAsArray = $this->getComposerJsonAsArray($directoryInfo->getPathname());

        $organizationNameDashed = $this->getOrganizationNameFromComposer($composerJsonAsArray);
        $organizationName = $this->camelCase($organizationNameDashed);

        $moduleNameDashed = $this->getModuleNameFromComposer($composerJsonAsArray);
        $moduleName = $this->camelCase($moduleNameDashed);

        $organizationTransfer = $this->buildOrganizationTransfer($organizationName, $organizationNameDashed);
        $applicationTransfer = $this->buildApplicationTransfer($directoryInfo);

        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer
            ->setOrganization($organizationTransfer)
            ->setApplication($applicationTransfer);

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
     * @return \Generated\Shared\Transfer\ApplicationTransfer
     */
    protected function buildApplicationTransfer(SplFileInfo $directoryInfo): ApplicationTransfer
    {
        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer
            ->setName($this->getApplicationNameFromDirectory($directoryInfo));

        return $applicationTransfer;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getComposerJsonAsArray(string $path): array
    {
        $pathToComposerJson = sprintf('%s/composer.json', $path);
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
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return string
     */
    protected function getApplicationNameFromDirectory(SplFileInfo $directoryInfo): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $directoryInfo->getRealPath());
        $vendorPosition = array_search('vendor', $pathFragments);

        $applicationName = $pathFragments[$vendorPosition + 2];

        return $applicationName;
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
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     *
     * @return bool
     */
    protected function matches(ModuleTransfer $moduleTransfer, ModuleFilterTransfer $moduleFilterTransfer): bool
    {
        $accepted = true;

        if (!$this->matchesOrganization($moduleFilterTransfer, $moduleTransfer->getOrganization())) {
            $accepted = false;
        }
        if (!$this->matchesApplication($moduleFilterTransfer, $moduleTransfer->getApplication())) {
            $accepted = false;
        }
        if (!$this->matchesModule($moduleFilterTransfer, $moduleTransfer)) {
            $accepted = false;
        }

        return $accepted;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param \Generated\Shared\Transfer\OrganizationTransfer $organizationTransfer
     *
     * @return bool
     */
    protected function matchesOrganization(ModuleFilterTransfer $moduleFilterTransfer, OrganizationTransfer $organizationTransfer): bool
    {
        if ($moduleFilterTransfer->getOrganization() === null) {
            return true;
        }

        return $this->match($moduleFilterTransfer->getOrganization()->getName(), $organizationTransfer->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     *
     * @return bool
     */
    protected function matchesApplication(ModuleFilterTransfer $moduleFilterTransfer, ApplicationTransfer $applicationTransfer): bool
    {
        if ($moduleFilterTransfer->getApplication() === null) {
            return true;
        }

        return $this->match($moduleFilterTransfer->getApplication()->getName(), $applicationTransfer->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer $moduleFilterTransfer
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return bool
     */
    protected function matchesModule(ModuleFilterTransfer $moduleFilterTransfer, ModuleTransfer $moduleTransfer): bool
    {
        if ($moduleFilterTransfer->getModule() === null) {
            return true;
        }

        return $this->match($moduleFilterTransfer->getModule()->getName(), $moduleTransfer->getName());
    }

    /**
     * @param string $search
     * @param string $given
     *
     * @return bool
     */
    protected function match(string $search, string $given): bool
    {
        if ($search === $given) {
            return true;
        }

        if (mb_strpos($search, '*') !== 0) {
            $search = '^' . $search;
        }

        if (mb_strpos($search, '*') === 0) {
            $search = mb_substr($search, 1);
        }

        if (mb_substr($search, -1) !== '*') {
            $search .= '$';
        }

        if (mb_substr($search, -1) === '*') {
            $search = mb_substr($search, 0, mb_strlen($search) - 1);
        }

        return preg_match(sprintf('/%s/', $search), $given);
    }
}
