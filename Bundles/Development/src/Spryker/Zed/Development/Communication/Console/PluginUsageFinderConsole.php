<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Business\DevelopmentBusinessFactory getFactory()
 */
class PluginUsageFinderConsole extends Console
{
    protected const COMMAND_NAME = 'dev:plugin:usage';
    protected const ARGUMENT_MODULE = 'module';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->addArgument(static::ARGUMENT_MODULE, InputArgument::OPTIONAL, 'Module to run checks for. You must use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar` or `Spryker.all` or `Spryker.M`. The latter syntax will find all modules starting with M, this can be more explicit by using more letters matching the modules you want to run checks for.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pluginUsages = [];

        $finder = $this->getFinder();
        foreach ($finder as $splFileObject) {
            $pluginUsages = $this->addPluginUsages($pluginUsages, $splFileObject);
        }

        foreach ($pluginUsages as $application => $modules) {
            foreach ($modules as $moduleName => $usedPlugins) {
                $applicationModule = sprintf('%s %s', $application, $moduleName);
                $table = new Table($output);
                $table->setHeaders(['Module/Application', 'Dependend Module', 'Organization', 'Application', 'Plugin name']);

                foreach ($usedPlugins as $usedPlugin) {
                    array_unshift($usedPlugin, $applicationModule);
                    $table->addRow($usedPlugin);
                }

                $table->render();
            }
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return \Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    protected function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()->in(APPLICATION_SOURCE_DIR)->name('/DependencyProvider.php/');

        return $finder;
    }

    /**
     * @param array $pluginUsages
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return array
     */
    protected function addPluginUsages(array $pluginUsages, SplFileInfo $splFileInfo): array
    {
        preg_match_all('/use (.*?);/', $splFileInfo->getContents(), $matches, PREG_SET_ORDER);
        if (count($matches) === 0) {
            return $pluginUsages;
        }

        $usedPlugins = [];

        foreach ($matches as $match) {
            if (preg_match('/Plugin/', $match[1])) {
                $usedPlugins[] = $this->getPluginInformation($match[1]);
            }
        }

        if (count($usedPlugins) === 0) {
            return $pluginUsages;
        }

        $applicationName = $this->getApplicationName($splFileInfo);
        $moduleName = $this->getModuleName($splFileInfo);

        $pluginUsages[$applicationName][$moduleName] = $usedPlugins;

        return $pluginUsages;
    }

    /**
     * @param string $pluginClassName
     *
     * @return array
     */
    protected function getPluginInformation(string $pluginClassName): array
    {
        $pluginClassNameFragments = explode('\\', $pluginClassName);

        return [
            'organization' => $pluginClassNameFragments[0],
            'application' => $pluginClassNameFragments[1],
            'module' => $pluginClassNameFragments[2],
            'pluginClassName' => $pluginClassName,
        ];
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string
     */
    protected function getApplicationName(SplFileInfo $splFileInfo): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $splFileInfo->getPathname());

        $organizationPosition = array_search('Pyz', $pathFragments);

        return $pathFragments[$organizationPosition + 1];
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $splFileInfo
     *
     * @return string
     */
    protected function getModuleName(SplFileInfo $splFileInfo): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $splFileInfo->getPathname());

        $organizationPosition = array_search('Pyz', $pathFragments);

        return $pathFragments[$organizationPosition + 2];
    }
}
