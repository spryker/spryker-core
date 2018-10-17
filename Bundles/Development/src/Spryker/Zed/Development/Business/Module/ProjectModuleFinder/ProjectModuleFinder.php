<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Module\ProjectModuleFinder;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ProjectModuleFinder implements ProjectModuleFinderInterface
{
    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleFilterTransfer|null $moduleFilterTransfer
     *
     * @return array
     */
    public function getProjectModules(?ModuleFilterTransfer $moduleFilterTransfer = null): array
    {
        $moduleCollection = [];

        $projectDirectories = $this->getProjectDirectories();

        if (count($projectDirectories) === 0) {
            return $moduleCollection;
        }

        foreach ($this->getProjectModuleFinder($projectDirectories) as $directoryInfo) {
            $moduleTransfer = $this->getModuleTransfer($directoryInfo);

            if ($moduleFilterTransfer !== null && !$this->matches($moduleTransfer, $moduleFilterTransfer)) {
                continue;
            }

            $moduleCollection[$this->buildOrganizationModuleKey($moduleTransfer)][] = $moduleTransfer;
        }

        ksort($moduleCollection);

        return $moduleCollection;
    }

    /**
     * @return array
     */
    protected function getProjectDirectories(): array
    {
        $projectOrganizationModuleDirectories = [];
        foreach ($this->config->getProjectNamespaces() as $projectOrganization) {
            foreach ($this->config->getApplications() as $application) {
                $projectOrganizationModuleDirectories[] = sprintf('%s/%s/%s/', APPLICATION_SOURCE_DIR, $projectOrganization, $application);
            }
        }

        return array_filter($projectOrganizationModuleDirectories, 'is_dir');
    }

    /**
     * @param array $projectOrganizationModuleDirectories
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]|\Symfony\Component\Finder\Finder
     */
    protected function getProjectModuleFinder(array $projectOrganizationModuleDirectories): Finder
    {
        $finder = new Finder();
        $finder
            ->directories()
            ->depth('== 0')
            ->in($projectOrganizationModuleDirectories)
            ->sort($this->getFilenameSortCallback());

        return $finder;
    }

    /**
     * @return callable
     */
    protected function getFilenameSortCallback(): callable
    {
        return function (SplFileInfo $fileOne, SplFileInfo $fileTwo) {
            return strcmp($fileOne->getRealpath(), $fileTwo->getRealpath());
        };
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function getModuleTransfer(SplFileInfo $directoryInfo): ModuleTransfer
    {
        return $this->buildModuleTransferFromDirectoryInformation($directoryInfo);
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransferFromDirectoryInformation(SplFileInfo $directoryInfo): ModuleTransfer
    {
        $organizationName = $this->getOrganizationNameFromDirectory($directoryInfo);
        $applicationName = $this->getApplicationNameFromDirectory($directoryInfo);
        $moduleName = $this->getModuleNameFromDirectory($directoryInfo);

        return $this->buildModuleTransfer($organizationName, $applicationName, $moduleName);
    }

    /**
     * @param string $organizationName
     * @param string $applicationName
     * @param string $moduleName
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    protected function buildModuleTransfer(string $organizationName, string $applicationName, string $moduleName): ModuleTransfer
    {
        $moduleTransfer = new ModuleTransfer();
        $moduleTransfer
            ->setName($moduleName)
            ->setPath(dirname(APPLICATION_SOURCE_DIR) . DIRECTORY_SEPARATOR)
            ->setOrganization($this->buildOrganizationTransfer($organizationName))
            ->setApplication($this->buildApplicationTransfer($applicationName));

        return $moduleTransfer;
    }

    /**
     * @param string $organizationName
     *
     * @return \Generated\Shared\Transfer\OrganizationTransfer
     */
    protected function buildOrganizationTransfer(string $organizationName): OrganizationTransfer
    {
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName($organizationName)
            ->setIsProject(true);

        return $organizationTransfer;
    }

    /**
     * @param string $applicationName
     *
     * @return \Generated\Shared\Transfer\ApplicationTransfer
     */
    protected function buildApplicationTransfer(string $applicationName): ApplicationTransfer
    {
        $applicationTransfer = new ApplicationTransfer();
        $applicationTransfer
            ->setName($applicationName);

        return $applicationTransfer;
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $directoryInfo
     *
     * @return string
     */
    protected function getOrganizationNameFromDirectory(SplFileInfo $directoryInfo): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $directoryInfo->getRealPath());
        $srcPosition = array_search('src', $pathFragments);

        $organizationName = $pathFragments[$srcPosition + 1];

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
        $srcPosition = array_search('src', $pathFragments);

        $organizationName = $pathFragments[$srcPosition + 2];

        return $organizationName;
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
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    protected function buildOrganizationModuleKey(ModuleTransfer $moduleTransfer): string
    {
        return sprintf('%s.%s', $moduleTransfer->getOrganization()->getName(), $moduleTransfer->getName());
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
