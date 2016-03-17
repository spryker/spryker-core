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
            mkdir($configDirectory, 0775, true);
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

        $fileName = $this->getTargetDirectory() . '/config_default-test.php';

        file_put_contents($fileName, $configHeader . implode(PHP_EOL, $configUseStatements) . $configBody);
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
