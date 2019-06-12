<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Setup\Business\Model\Cronjobs;
use Spryker\Zed\Setup\Business\Model\DirectoryRemover;
use Spryker\Zed\Setup\Business\Model\GeneratedDirectory;
use Spryker\Zed\Setup\Communication\Console\DeployPreparePropelConsole;
use Spryker\Zed\Setup\Communication\Console\InstallConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsDisableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsEnableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsGenerateConsole;
use Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;
use Spryker\Zed\Setup\SetupDependencyProvider;

/**
 * @method \Spryker\Zed\Setup\SetupConfig getConfig()
 */
class SetupBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @deprecated Method will be removed without replacement.
     *
     * @return \Spryker\Zed\Setup\Business\Model\Cronjobs
     */
    public function createModelCronjobs()
    {
        $config = $this->getConfig();

        return new Cronjobs($config);
    }

    /**
     * @deprecated Use createGeneratedDirectoryModel() instead
     *
     * @return \Spryker\Zed\Setup\Business\Model\DirectoryRemoverInterface
     */
    public function createModelGeneratedDirectoryRemover()
    {
        return $this->createDirectoryRemover(
            $this->getConfig()->getGeneratedDirectory()
        );
    }

    /**
     * @deprecated Use createGeneratedDirectoryModel() instead
     *
     * @param string $path
     *
     * @return \Spryker\Zed\Setup\Business\Model\DirectoryRemoverInterface
     */
    protected function createDirectoryRemover($path)
    {
        return new DirectoryRemover($path);
    }

    /**
     * @deprecated Hook in commands manually on project level
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return [
            $this->createRunnerConsole(),
            $this->createRemoveGeneratedDirectoryConsole(),
            $this->createInstallConsole(),
            $this->createJenkinsEnableConsole(),
            $this->createJenkinsDisableConsole(),
            $this->createJenkinsGenerateConsole(),
            $this->createDeployPreparePropelConsole(),
        ];
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole
     */
    protected function createRunnerConsole()
    {
        return new RunnerConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole
     */
    protected function createRemoveGeneratedDirectoryConsole()
    {
        return new RemoveGeneratedDirectoryConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\InstallConsole
     */
    protected function createInstallConsole()
    {
        return new InstallConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\JenkinsEnableConsole
     */
    protected function createJenkinsEnableConsole()
    {
        return new JenkinsEnableConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\JenkinsDisableConsole
     */
    protected function createJenkinsDisableConsole()
    {
        return new JenkinsDisableConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\JenkinsGenerateConsole
     */
    protected function createJenkinsGenerateConsole()
    {
        return new JenkinsGenerateConsole();
    }

    /**
     * @deprecated Will be removed with next major release
     *
     * @return \Spryker\Zed\Setup\Communication\Console\DeployPreparePropelConsole
     */
    protected function createDeployPreparePropelConsole()
    {
        return new DeployPreparePropelConsole();
    }

    /**
     * @return \Spryker\Zed\Setup\Business\Model\GeneratedDirectoryInterface
     */
    public function createGeneratedDirectoryModel()
    {
        return new GeneratedDirectory(
            $this->getConfig()->getGeneratedDirectory(),
            $this->getFileSystem(),
            $this->getFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function getFileSystem()
    {
        return $this->getProvidedDependency(SetupDependencyProvider::SYMFONY_FILE_SYSTEM);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder()
    {
        return $this->getProvidedDependency(SetupDependencyProvider::SYMFONY_FINDER);
    }
}
