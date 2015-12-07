<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Business\CodeStyleSniffer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Zend\Filter\Word\UnderscoreToCamelCase;

class CodeStyleSniffer
{

    const XML_RULESET = 'ruleset.xml';

    const BUNDLE_ALL = 'all';

    const OPTION_FIX = 'fix';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_VERBOSE = 'verbose';

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
    public function checkBundleCodeStyle($bundle, array $options = [])
    {
        $path = $this->resolvePath($bundle);

        if (!is_dir($path)) {
            $message = 'This path does not exist';
            if (!empty($bundle)) {
                $message = 'This bundle does not exist';
            }

            throw new \ErrorException($message);
        }

        $this->runFixerCommand($path, $options);
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
    protected function resolvePath($bundle) {
        if ($bundle) {
            if ($bundle === self::BUNDLE_ALL) {
                return $this->pathToBundles;
            }

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
     * @param array $options
     *
     * @return void
     */
    protected function runFixerCommand($path, array $options)
    {
        $pathToFiles = rtrim($path, DIRECTORY_SEPARATOR);

        $config = ' --standard=' . __DIR__ . '/ruleset.xml';
        if ($options[self::OPTION_VERBOSE]) {
            $config .= ' -v';
        }

        $command = $options[self::OPTION_FIX] ? 'phpcbf' : 'phpcs';
        $command = 'vendor/bin/' . $command . ' ' . $pathToFiles . $config;

        if (!empty($options[self::OPTION_DRY_RUN])) {
            echo $command;
            return;
        }

        $process = new Process($command, $this->applicationRoot, null, null, 4800);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }

}
