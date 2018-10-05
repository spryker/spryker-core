<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Config\Helper;

use Codeception\Module;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ConfigInit extends Module
{
    /**
     * @return void
     */
    public function _initialize()
    {
        if (isset($this->config['enabled']) && $this->config['enabled']) {
            $this->copyBundleConfigurationFiles();
            $this->generateConfigDefaultFile();
        }
    }

    /**
     * Copy all configuration files like `stores.php` into "virtual project"
     *
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
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    private function getConfigFiles()
    {
        $configDirectories = $this->getSourceDirectories();
        $finder = new Finder();
        $finder->files()
            ->in($configDirectories)
            ->notName('config_*');

        return $finder;
    }

    /**
     * @return string
     */
    private function getSourceDirectories()
    {
        $configPaths = [
            APPLICATION_ROOT_DIR . '/../*/config/',
        ];

        $configPaths = $this->addBundleToTestConfigDirectory($configPaths);

        return $configPaths;
    }

    /**
     * @param array $configPaths
     *
     * @return array
     */
    private function addBundleToTestConfigDirectory(array $configPaths)
    {
        $bundleToTestConfigDirectory = APPLICATION_ROOT_DIR . '/../../../config/';
        if (is_dir($bundleToTestConfigDirectory)) {
            $configPaths[] = $bundleToTestConfigDirectory;
        }

        return $configPaths;
    }

    /**
     * Path to "virtual project"
     *
     * @return string
     */
    private function getTargetDirectory()
    {
        return APPLICATION_ROOT_DIR . '/config/Shared/';
    }

    /**
     * Merge all config_* files from bundles and copy into new config file
     * Config class will load this file within the "virtual project"
     *
     * @return void
     */
    private function generateConfigDefaultFile()
    {
        $this->clearGeneratedConfigFile();
        $this->writeConfigFile($this->generateConfig());
    }

    /**
     * @return void
     */
    private function clearGeneratedConfigFile()
    {
        $this->writeConfigFile('');
    }

    /**
     * @return string
     */
    private function generateConfig()
    {
        $finder = $this->getConfigDefaultFiles();
        $configHeader = '<?php' . PHP_EOL . PHP_EOL;
        $configUseStatements = [];
        $configBody = '';
        foreach ($finder as $file) {
            $content = str_replace('<?php', '', $file->getContents());
            $useStatements = [];
            preg_match_all('/use\s(.*?);/', $content, $useStatements, PREG_SET_ORDER);

            foreach ($useStatements as $useStatement) {
                $content = str_replace($useStatement[0] . PHP_EOL, '', $content);
                $configUseStatements[$useStatement[1]] = $useStatement[0];
            }

            $configBody .= $content;
        }

        return $configHeader . implode(PHP_EOL, $configUseStatements) . $configBody;
    }

    /**
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    private function getConfigDefaultFiles()
    {
        $finder = new Finder();
        $configDirectories = $this->getSourceDirectories();
        $finder->files()
            ->in($configDirectories)
            ->name('config_*');

        return $finder;
    }

    /**
     * @param string $fileContent
     *
     * @return void
     */
    private function writeConfigFile($fileContent)
    {
        file_put_contents($this->getConfigFilePath(), $fileContent);
    }

    /**
     * @return string
     */
    private function getConfigFilePath()
    {
        return $this->getTargetDirectory() . '/config_default-test.php';
    }
}
