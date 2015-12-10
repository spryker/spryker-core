<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup;

use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Setup\Communication\Console\GenerateClientIdeAutoCompletionConsole;
use SprykerFeature\Zed\Setup\Communication\Console\GenerateIdeAutoCompletionConsole;
use SprykerFeature\Zed\Setup\Communication\Console\GenerateYvesIdeAutoCompletionConsole;
use SprykerFeature\Zed\Setup\Communication\Console\GenerateZedIdeAutoCompletionConsole;
use SprykerFeature\Zed\Setup\Communication\Console\InstallConsole;
use SprykerFeature\Zed\Setup\Communication\Console\Npm\RunnerConsole;
use SprykerFeature\Zed\Setup\Communication\Console\RemoveGeneratedDirectoryConsole;
use Symfony\Component\Console\Command\Command;
use SprykerFeature\Zed\Setup\Communication\Console\DeployPreparePropelConsole;
use SprykerFeature\Zed\Setup\Communication\Console\JenkinsDisableConsole;
use SprykerFeature\Zed\Setup\Communication\Console\JenkinsEnableConsole;
use SprykerFeature\Zed\Setup\Communication\Console\JenkinsGenerateConsole;

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
