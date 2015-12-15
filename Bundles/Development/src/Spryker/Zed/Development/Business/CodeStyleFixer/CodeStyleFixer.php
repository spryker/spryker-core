<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\CodeStyleFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeStyleFixer
{

    const PHP_CS_CONFIG_FILE_NAME = '.php_cs';

    const PHP_CS_CACHE_CONFIG_FILE_NAME = '.php_cs.cache';

    const OPTION_VERBOSE = 'verbose';

    const OPTION_CLEAR = 'clear';

    const BUNDLE_ALL = 'all';

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
     * @param array $options
     *
     * @throws \ErrorException
     *
     * @return void
     */
    public function fixCodeStyle($bundle, array $options = [])
    {
        if (!$bundle) {
            if ($options[self::OPTION_CLEAR]) {
                $this->clearCacheFile($this->applicationRoot);
            }
            $this->runFixerCommand($this->applicationRoot, $this->applicationRoot, $options);

            return;
        }

        if ($bundle === self::BUNDLE_ALL) {
            $this->copyPhpCsFixerConfigToBundle($this->pathToBundles, $options[self::OPTION_CLEAR]);
            $this->runFixerCommand($this->pathToBundles, $this->getPathToCore(), $options);

            return;
        }

        $bundle = $this->normalizeBundleName($bundle);
        $pathToBundle = $this->getPathToBundle($bundle);

        if (!is_dir($pathToBundle)) {
            throw new \ErrorException('This bundle does not exist');
        }

        $this->copyPhpCsFixerConfigToBundle($pathToBundle, $options[self::OPTION_CLEAR]);
        $this->runFixerCommand($pathToBundle, $this->getPathToCore(), $options);
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function normalizeBundleName($bundle)
    {
        $filter = new UnderscoreToCamelCase();

        return ucfirst($filter->filter($bundle));
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getPathToBundle($bundle)
    {
        return $this->pathToBundles . $bundle . DIRECTORY_SEPARATOR;
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

        $this->clearCacheFile($path);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    protected function clearCacheFile($path)
    {
        $cacheFile = $path . self::PHP_CS_CACHE_CONFIG_FILE_NAME;
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * @param string $path
     * @param string $rootPath
     * @param array $options
     *
     * @return void
     */
    protected function runFixerCommand($path, $rootPath, array $options)
    {
        $arguments = '';
        if ($options[self::OPTION_VERBOSE]) {
            $arguments = ' -vvv';
        }

        if ($path === $this->applicationRoot) {
            $path = '';
        }
        if ($path) {
            $path = ' ' . rtrim($path, DIRECTORY_SEPARATOR);
        }

        $command = $this->applicationRoot . 'vendor/bin/php-cs-fixer fix' . $path . $arguments;
        if ($options[self::OPTION_VERBOSE]) {
            echo $command . PHP_EOL;
        }

        $process = new Process($command, $rootPath, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
