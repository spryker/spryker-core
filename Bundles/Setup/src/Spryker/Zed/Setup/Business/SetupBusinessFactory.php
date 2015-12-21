<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Setup\Business\Model\DirectoryRemover;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use Spryker\Zed\Setup\Business\Model\Cronjobs;
use Spryker\Zed\Setup\Business\Model\DirectoryRemoverInterface;
use Spryker\Zed\Setup\Communication\Console\DeployPreparePropelConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateClientIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateYvesIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateZedIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\InstallConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsDisableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsEnableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsGenerateConsole;
use Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;
use Spryker\Zed\Setup\SetupConfig;
use Spryker\Zed\Setup\SetupDependencyProvider;
use Symfony\Component\Console\Command\Command;

/**
 * @method SetupConfig getConfig()
 */
class SetupBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Cronjobs
     */
    public function createModelCronjobs()
    {
        $config = $this->getConfig();

        return new Cronjobs($config);
    }

    /**
     * @return DirectoryRemoverInterface
     */
    public function createModelGeneratedDirectoryRemover()
    {
        return $this->createDirectoryRemover(
            $this->getConfig()->getGeneratedDirectory()
        );
    }

    /**
     * @param string $path
     *
     * @return DirectoryRemoverInterface
     */
    private function createDirectoryRemover($path)
    {
        return new DirectoryRemover($path);
    }

    /**
     * @throws \ErrorException
     *
     * @return Repeater
     */
    public function createTransferObjectRepeater()
    {
        return $this->getProvidedDependency(SetupDependencyProvider::PLUGIN_TRANSFER_OBJECT_REPEATER);
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [
            $this->createGenerateIdeAutoCompletionConsole(),
            $this->createGenerateZedIdeAutoCompletionConsole(),
            $this->createGenerateYvesIdeAutoCompletionConsole(),
            $this->createGenerateClientIdeAutoCompletionConsole(),
            $this->createRunnerConsole(),
            $this->createRemoveGeneratedDirectoryConsole(),
            $this->createInstallConsole(),
            $this->createJenkinsEnableConsole(),
            $this->createJenkinsDisableConsole(),
            $this->createJenkinsGenerateConsole(),
            $this->createDeployPreparePropelConsole()
        ];
    }

    /**
     * @return GenerateIdeAutoCompletionConsole
     */
    protected function createGenerateIdeAutoCompletionConsole()
    {
        return new GenerateIdeAutoCompletionConsole();
    }

    /**
     * @return GenerateZedIdeAutoCompletionConsole
     */
    protected function createGenerateZedIdeAutoCompletionConsole()
    {
        return new GenerateZedIdeAutoCompletionConsole();
    }

    /**
     * @return GenerateYvesIdeAutoCompletionConsole
     */
    protected function createGenerateYvesIdeAutoCompletionConsole()
    {
        return new GenerateYvesIdeAutoCompletionConsole();
    }

    /**
     * @return GenerateClientIdeAutoCompletionConsole
     */
    protected function createGenerateClientIdeAutoCompletionConsole()
    {
        return new GenerateClientIdeAutoCompletionConsole();
    }

    /**
     * @return RunnerConsole
     */
    protected function createRunnerConsole()
    {
        return new RunnerConsole();
    }

    /**
     * @return RemoveGeneratedDirectoryConsole
     */
    protected function createRemoveGeneratedDirectoryConsole()
    {
        return new RemoveGeneratedDirectoryConsole();
    }

    /**
     * @return InstallConsole
     */
    protected function createInstallConsole()
    {
        return new InstallConsole();
    }

    /**
     * @return JenkinsEnableConsole
     */
    protected function createJenkinsEnableConsole()
    {
        return new JenkinsEnableConsole();
    }

    /**
     * @return JenkinsDisableConsole
     */
    protected function createJenkinsDisableConsole()
    {
        return new JenkinsDisableConsole();
    }

    /**
     * @return JenkinsGenerateConsole
     */
    protected function createJenkinsGenerateConsole()
    {
        return new JenkinsGenerateConsole();
    }

    /**
     * @return DeployPreparePropelConsole
     */
    protected function createDeployPreparePropelConsole()
    {
        return new DeployPreparePropelConsole();
    }

}
