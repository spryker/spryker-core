<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacadeInterface getFacade()
 */
class InitializeDatabaseConsole extends Console
{
    public const COMMAND_NAME = 'setup:init-db';
    public const DESCRIPTION = 'Fill the database with required data';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);
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
     * @param string $className
     *
     * @return mixed
     */
    protected function getPluginNameFromClass($className)
    {
        $pattern = '#^.+?\\\.+?\\\(.+?)\\\.+$#i';
        return preg_replace($pattern, '${1}', $className);
    }
}
