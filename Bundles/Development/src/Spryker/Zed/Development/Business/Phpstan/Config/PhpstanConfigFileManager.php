<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Phpstan\Config;

use Nette\DI\Config\Loader;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Filesystem\Filesystem;

class PhpstanConfigFileManager implements PhpstanConfigFileManagerInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected DevelopmentConfig $config;

    /**
     * @var \Nette\DI\Config\Loader
     */
    protected Loader $configLoader;

    /**
     * @var \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileSaverInterface
     */
    protected PhpstanConfigFileSaverInterface $phpstanConfigFileSaver;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Nette\DI\Config\Loader $configLoader
     * @param \Spryker\Zed\Development\Business\Phpstan\Config\PhpstanConfigFileSaverInterface $phpstanConfigFileSaver
     */
    public function __construct(
        Filesystem $filesystem,
        DevelopmentConfig $config,
        Loader $configLoader,
        PhpstanConfigFileSaverInterface $phpstanConfigFileSaver
    ) {
        $this->filesystem = $filesystem;
        $this->config = $config;
        $this->configLoader = $configLoader;
        $this->phpstanConfigFileSaver = $phpstanConfigFileSaver;
    }

    /**
     * @param array<\SplFileInfo> $configFiles
     * @param string $newConfigFileName
     *
     * @return string
     */
    public function merge(array $configFiles, string $newConfigFileName): string
    {
        return $this->saveConfig($newConfigFileName, $this->mergeConfigs($configFiles));
    }

    /**
     * @param string $configFilePath
     *
     * @return bool
     */
    public function isMergedConfigFile(string $configFilePath): bool
    {
        return strpos($configFilePath, $this->config->getPathToPhpstanModuleTemporaryConfigFolder()) === 0;
    }

    /**
     * @param string $configFilePath
     *
     * @return void
     */
    public function deleteConfigFile(string $configFilePath): void
    {
        $this->filesystem->remove($configFilePath);
    }

    /**
     * @param array<\SplFileInfo> $configFiles
     *
     * @return array
     */
    protected function mergeConfigs(array $configFiles): array
    {
        $mergedConfig = [];

        foreach ($configFiles as $configFile) {
            $mergedConfig = $this->addConfig($mergedConfig, $this->configLoader->load($configFile->getPathname()));
        }

        return $mergedConfig;
    }

    /**
     * @param array $replacementsConfig
     * @param array $baseConfig
     *
     * @return array
     */
    protected function addConfig(array $replacementsConfig, array $baseConfig): array
    {
        return array_replace_recursive($baseConfig, $replacementsConfig);
    }

    /**
     * @param string $newConfigFileName
     * @param array $mergedConfig
     *
     * @return string
     */
    protected function saveConfig(string $newConfigFileName, array $mergedConfig): string
    {
        $directory = $this->config->getPathToPhpstanModuleTemporaryConfigFolder();

        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory);
        }

        $newConfigFilePath = $directory . $newConfigFileName;
        $this->phpstanConfigFileSaver->save(
            $newConfigFilePath . $this->config->getPhpstanConfigFilename(),
            $mergedConfig,
        );

        return $newConfigFilePath;
    }
}
