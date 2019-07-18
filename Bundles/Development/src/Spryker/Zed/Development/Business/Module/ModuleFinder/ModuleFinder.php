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
use Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcherInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;
use Zend\Filter\Word\DashToCamelCase;

/**
 * @deprecated Use `spryker/module-finder` instead.
 */
class ModuleFinder implements ModuleFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcherInterface
     */
    protected $moduleMatcher;

    /**
     * @var \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected static $moduleTransferCollection;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Module\ModuleMatcher\ModuleMatcherInterface $moduleMatcher
     */
    public function __construct(DevelopmentConfig $config, ModuleMatcherInterface $moduleMatcher)
    {
        $this->config = $config;
        $this->moduleMatcher = $moduleMatcher;
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
            if (in_array($this->camelCase($directoryInfo->getFilename()), $this->config->getInternalNamespacesList(), true)) {
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
        if ($moduleFilterTransfer !== null && !$this->moduleMatcher->matches($moduleTransfer, $moduleFilterTransfer)) {
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
    protected function getModuleFinder(): Finder
    {
        return (new Finder())->directories()->depth('== 0')->in($this->getModuleDirectories());
    }

    /**
     * @return array
     */
    protected function getModuleDirectories(): array
    {
        $pathToInternalNamespace = $this->config->getPathsToInternalNamespace();

        return array_values(array_filter($pathToInternalNamespace, 'is_dir'));
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

        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer
            ->setOrganization($organizationTransfer);

        $moduleTransfer = $this->addApplications($moduleTransfer);

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

        $moduleTransfer = $this->buildModuleTransfer($moduleName, $moduleNameDashed, $directoryInfo);
        $moduleTransfer
            ->setOrganization($organizationTransfer);

        $moduleTransfer = $this->addApplications($moduleTransfer);

        return $moduleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function addApplications(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        $lookupDirectory = sprintf('%s/src/%s/', $moduleTransfer->getPath(), $moduleTransfer->getOrganization()->getName());
        if (!is_dir($lookupDirectory)) {
            return $moduleTransfer;
        }
        $applicationFinder = new Finder();
        $applicationFinder->in($lookupDirectory)->depth('== 0');

        foreach ($applicationFinder as $applicationDirectoryInfo) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($applicationDirectoryInfo->getRelativePathname());
            $moduleTransfer->addApplication($applicationTransfer);
        }

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
            ->setPath($directoryInfo->getRealPath() . DIRECTORY_SEPARATOR)
            ->setIsStandalone(false);

        return $moduleTransfer;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getComposerJsonAsArray(string $path): array
    {
        $pathToComposerJson = sprintf('%s/composer.json', $path);
        if (!is_file($pathToComposerJson)) {
            return [];
        }
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
    protected function camelCase(string $value): string
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
    protected function dasherize(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filterChain->filter($value);
    }
}
