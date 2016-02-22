<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Config\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Spryker\Shared\Config\Config;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ConfigInit extends Module
{
    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param null $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        if (isset($this->config['enabled']) && $this->config['enabled']) {
            $this->copyBundleConfigurationFiles();
            $this->initConfigDefault();
        }
    }

    /**
     * @return void
     */
    private function copyBundleConfigurationFiles()
    {
        $files = $this->getConfigFiles();
        $filesystem = new Filesystem();

        foreach ($files as $file) {
            $filePath = $this->getTargetDirectory() . '/' . $file->getFilename();
            $filesystem->dumpFile($filePath, $file->getContents());
        }
    }

    /**
     * @return SplFileInfo[]
     */
    private function getConfigFiles()
    {
        $configDirectories = $this->getSourceDirectories();
        $configDirectory = $this->getTargetDirectory();
        if (!is_dir($configDirectory)) {
            mkdir($configDirectory, 0777, true);
        }

        $finder = new Finder();
        $finder->files()->in($configDirectories)->exclude(
            APPLICATION_ROOT_DIR . '/../testify/'
        )->notName('config_*');

        return $finder;
    }

    /**
     * @return string
     */
    private function getSourceDirectories()
    {
        $configDirectories = APPLICATION_ROOT_DIR . '/../*/config/';

        return $configDirectories;
    }

    /**
     * @return string
     */
    private function getTargetDirectory()
    {
        $configDirectory = APPLICATION_ROOT_DIR . '/config/Shared/';

        return $configDirectory;
    }

    /**
     * @return void
     */
    private function initConfigDefault()
    {
        $finder = $this->getConfigDefaultFiles();

        $config = new \ArrayObject();
        foreach ($finder as $file) {
            include $file->getPathname();
        }

        Config::getInstance($config);
    }

    /**
     * @return SplFileInfo[]
     */
    private function getConfigDefaultFiles()
    {
        $finder = new Finder();
        $configDirectories = $this->getSourceDirectories();
        $finder->files()->in($configDirectories)->exclude(
            APPLICATION_ROOT_DIR . '/../testify/'
        )->name('config_*');

        return $finder;
    }

}
