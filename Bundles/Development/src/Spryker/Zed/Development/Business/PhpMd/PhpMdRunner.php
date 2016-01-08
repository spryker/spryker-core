<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Business\PhpMd;

use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class PhpMdRunner
{

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
     *
     * @throws \ErrorException
     *
     * @return void
     */
    public function run($bundle)
    {
        $path = $this->resolvePath($bundle);

        if (!is_dir($path)) {
            $message = 'This path does not exist';
            if (!empty($bundle)) {
                $message = 'This bundle does not exist';
            }

            throw new \ErrorException($message);
        }

        $this->runPhpMdCommand($path);
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
    protected function resolvePath($bundle)
    {
        if ($bundle) {
            if ($bundle === self::BUNDLE_ALL) {
                return $this->pathToBundles;
            }

            $bundle = $this->normalizeBundleName($bundle);

            return $this->getPathToBundle($bundle);
        }

        return $this->applicationRoot;
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

    /**
     * @param string $path
     *
     * @return void
     */
    protected function runPhpMdCommand($path)
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR);

        $config = __DIR__ . '/ruleset.xml';
        $command = 'vendor/bin/phpmd ' . $pathToFiles . ' xml ' . $config;
        echo $command . PHP_EOL;
        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
