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

    const PHP_CS_CACHE_CONFIG_FILE_NAME = '.php_cs.cache';

    /**
     * @var string
     */
    protected $applicationRoot;

    /**
     * @var string
     */
    protected $pathToBundles;

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
     * @param string|null $bundle
     * @param bool $clear
     *
     * @return void
     */
    public function fixBundleCodeStyle($bundle, $clear = false)
    {
        if (!$bundle) {
            $this->copyPhpCsFixerConfigToBundle($this->pathToBundles, $clear);
            $this->runFixerCommand($this->pathToBundles);
            return;
        }

        $bundle = $this->normalizeBundleName($bundle);
        $path = $this->getPathToBundle($bundle);
        $this->copyPhpCsFixerConfigToBundle($path, $clear);
        $this->runFixerCommand($path);
    }

    /**
     * @param $bundle
     *
     * @return string
     */
    protected function normalizeBundleName($bundle)
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($bundle));
    }

    /**
     * @param $bundle
     *
     * @return string
     */
    protected function getPathToBundle($bundle)
    {
        return $this->pathToBundles . $bundle;
    }

    /*
     * @return string
     */
    protected function getPathToCore()
    {
        return dirname($this->pathToBundles) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $path
     * @param bool $clear
     *
     * @return void
     */
    protected function copyPhpCsFixerConfigToBundle($path, $clear = false)
    {
        $from = $this->getPathToCore() . self::PHP_CS_CONFIG_FILE_NAME;
        $to = $path . self::PHP_CS_CONFIG_FILE_NAME;

        if (!$clear && file_exists($to)) {
            $modifiedTimeTarget = filemtime($to);
            $modifiedTimeSource = filemtime($from);
            if ($modifiedTimeTarget >= $modifiedTimeSource) {
                return;
            }
        }

        $fileSystem = new Filesystem();
        $fileSystem->copy(
            $from,
            $to
        );

        $cacheFile = $path . self::PHP_CS_CACHE_CONFIG_FILE_NAME;
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * @param string $path
     *
     * @return void
     */
    protected function runFixerCommand($path)
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);
        $command = $this->applicationRoot . '/vendor/bin/php-cs-fixer fix ' . $path . ' -vvv';
        $process = new Process($command, $this->getPathToCore(), null, null, 3600);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
