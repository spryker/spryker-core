<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup;

use Spryker\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Setup\Communication\Console\GenerateClientIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateYvesIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\GenerateZedIdeAutoCompletionConsole;
use Spryker\Zed\Setup\Communication\Console\InstallConsole;
use Spryker\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use Spryker\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;
use Symfony\Component\Console\Command\Command;
use Spryker\Zed\Setup\Communication\Console\DeployPreparePropelConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsDisableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsEnableConsole;
use Spryker\Zed\Setup\Communication\Console\JenkinsGenerateConsole;

class SetupDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGIN_TRANSFER_OBJECT_REPEATER = 'plugin transfer object repeater';

    const COMMANDS = 'commands';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGIN_TRANSFER_OBJECT_REPEATER] = function () {
            return new Repeater();
        };

        $container[self::COMMANDS] = function ($container) {
            return $this->getConsoleCommands($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Command[]
     */
    protected function getConsoleCommands(Container $container)
    {
        return [
            new GenerateIdeAutoCompletionConsole(),
            new GenerateZedIdeAutoCompletionConsole(),
            new GenerateYvesIdeAutoCompletionConsole(),
            new GenerateClientIdeAutoCompletionConsole(),
            new RunnerConsole(),
            new RemoveGeneratedDirectoryConsole(),
            new InstallConsole(),
            new JenkinsEnableConsole(),
            new JenkinsDisableConsole(),
            new JenkinsGenerateConsole(),
            new DeployPreparePropelConsole(),
        ];
    }

}
