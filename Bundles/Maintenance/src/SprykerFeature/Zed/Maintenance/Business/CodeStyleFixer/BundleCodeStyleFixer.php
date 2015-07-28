<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\CodeStyleFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class BundleCodeStyleFixer
{

    const PHP_CS_CONFIG_FILE_NAME = '.php_cs';

    /**
     * @var string
     */
    private $applicationRoot;

    /**
     * @var string
     */
    private $pathToBundles;

    /**
     * @param string $applicationRoot
     * @param string $pathToBundles
     */
    public function __construct($applicationRoot, $pathToBundles)
    {
        $this->applicationRoot = $applicationRoot;
        $this->pathToBundles = $pathToBundles;
    }

    /**
     * @param string $bundle
     */
    public function fixBundleCodeStyle($bundle)
    {
        $bundle = $this->normalizeBundleName($bundle);
        $path = $this->getPathToBundle($bundle);
        $this->copyPhpCsFixerConfigToBundle($path);
        $this->runFixerCommand($path);
        $this->removePhpCsFixerConfigFromBundle($path);
    }

    /**
     * @param $bundle
     *
     * @return string
     */
    private function normalizeBundleName($bundle)
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($bundle));
    }

    /**
     * @param $bundle
     *
     * @return string
     */
    private function getPathToBundle($bundle)
    {
        return $this->pathToBundles . $bundle;
    }

    /**
     * @param string $path
     */
    private function copyPhpCsFixerConfigToBundle($path)
    {
        $fileSystem = new Filesystem();
        $fileSystem->copy(
            $this->applicationRoot . DIRECTORY_SEPARATOR . self::PHP_CS_CONFIG_FILE_NAME,
            $path . DIRECTORY_SEPARATOR . self::PHP_CS_CONFIG_FILE_NAME
        );
    }

    /**
     * @param string $path
     */
    private function removePhpCsFixerConfigFromBundle($path)
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(
            $path . DIRECTORY_SEPARATOR . self::PHP_CS_CONFIG_FILE_NAME
        );
    }

    /**
     * @param string $path
     */
    private function runFixerCommand($path)
    {
        $command = './vendor/bin/php-cs-fixer fix ' . $path . ' -vvv';
        $process = new Process($command, $this->applicationRoot, null, null, 3600);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
