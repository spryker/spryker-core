<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacadeInterface getFacade()
 * @method \Spryker\Zed\Installer\Communication\InstallerCommunicationFactory getFactory()
 */
class InitializeDatabaseConsole extends Console
{
    public const COMMAND_NAME = 'setup:init-db';
    public const DESCRIPTION = 'Fill the database with required data';
    public const OPTION_PLUGIN = 'plugin';
    public const OPTION_SHORTCUT_PLUGIN = 'p';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::OPTION_PLUGIN,
            static::OPTION_SHORTCUT_PLUGIN,
            InputOption::VALUE_IS_ARRAY + InputOption::VALUE_OPTIONAL,
            'Name of plugin to be installed'
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installerPlugins = $this->getInstallerPlugins();

        try {
            foreach ($installerPlugins as $plugin) {
                if (!$this->needToInstall($input, get_class($plugin))) {
                    continue;
                }
                $name = $this->getPluginNameFromClass(get_class($plugin));

                $output->writeln('Installing DB data for ' . $name);
                $plugin->install();
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return \Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface[]
     */
    protected function getInstallerPlugins()
    {
        return $this->getFacade()->getInstallerPlugins();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string $pluginName
     *
     * @return bool
     */
    protected function needToInstall(InputInterface $input, string $pluginName): bool
    {
        /** @var array $pluginsToInstall */
        $pluginsToInstall = $input->getOption(static::OPTION_PLUGIN);

        if (!$pluginsToInstall) {
            return true;
        }

        return in_array($this->getPluginShortNameFromClass($pluginName), $pluginsToInstall);
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    protected function getPluginNameFromClass($className)
    {
        $pattern = '#^.+?\\\.+?\\\(.+?)\\\.+$#i';

        return preg_replace($pattern, '${1}', $className);
    }

    /**
     * @param string $className
     *
     * @return string|null
     */
    public function getPluginShortNameFromClass(string $className): ?string
    {
        $path = explode('\\', $className);

        return array_pop($path);
    }
}
