<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use stdClass;
use Symfony\Component\Finder\SplFileInfo;

class AutoloadUpdater implements UpdaterInterface
{

    const AUTOLOAD_KEY = 'autoload';
    const AUTOLOAD_DEV_KEY = 'autoload-dev';

    const BASE_TESTS_DIR = 'tests';

    const SPRYKER_TEST_NAMESPACE = 'SprykerTest';
    /**
     * @var array
     */
    protected $deprecatedTestDirectoryKeys = [
        'Acceptance',
        'Functional',
        'Integration',
        'Unit',
    ];

    /**
     * @var array
     */
    protected $applications = [
        'Client',
        'Shared',
        'Yves',
        'Zed',
    ];

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson = $this->updateAutoloadForTests($composerJson, $composerJsonFile);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    protected function updateAutoloadForTests(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $bundlePath = dirname($composerJsonFile->getPathname());
        $composerJson = $this->updateAutoloadWithDefaultTestDirectory($composerJson, $bundlePath);

        $testDirectoryKeys = $this->buildTestDirectoryKeys();
        foreach ($testDirectoryKeys as $testDirectoryKey) {
            $composerJson = $this->updateAutoload($composerJson, $testDirectoryKey, $bundlePath);
        }

        $composerJson = $this->cleanupAutoload($composerJson, $bundlePath);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $bundlePath
     *
     * @return array
     */
    protected function updateAutoloadWithDefaultTestDirectory(array $composerJson, $bundlePath)
    {
        $directoryName = $this->getTestSubDirectoryName(static::SPRYKER_TEST_NAMESPACE, $bundlePath);

        if ($this->pathExists($directoryName)) {
            $pathParts = [
                static::BASE_TESTS_DIR,
                static::SPRYKER_TEST_NAMESPACE,
            ];

            $composerJson = $this->addAutoloadDevPsr4($composerJson);
            $composerJson[static::AUTOLOAD_DEV_KEY]['psr-4'][static::SPRYKER_TEST_NAMESPACE . '\\'] = $this->getDirectoryName($pathParts);
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $testDirectoryKey
     * @param string $bundlePath
     *
     * @return array
     */
    protected function updateAutoload(array $composerJson, $testDirectoryKey, $bundlePath)
    {
        $directoryName = $this->getTestSubDirectoryName($testDirectoryKey, $bundlePath);

        if ($this->pathExists($directoryName)) {
            $composerJson = $this->addAutoloadDevPsr0($composerJson);
            $composerJson[static::AUTOLOAD_DEV_KEY]['psr-0'][$testDirectoryKey] = static::BASE_TESTS_DIR . '/';
        }

        if (isset($composerJson[static::AUTOLOAD_KEY]['psr-0'][$testDirectoryKey])) {
            unset($composerJson[static::AUTOLOAD_KEY]['psr-0'][$testDirectoryKey]);
        }

        return $composerJson;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function pathExists($path)
    {
        return (is_dir($path) || is_file(rtrim($path, '/')));
    }

    /**
     * @return array
     */
    protected function buildTestDirectoryKeys()
    {
        $testDirectoryKeys = [];
        foreach ($this->deprecatedTestDirectoryKeys as $testDirectoryKey) {
            $testDirectoryKeys[] = $testDirectoryKey;
            foreach ($this->applications as $application) {
                $testDirectoryKeys[] = $application . $testDirectoryKey;
            }
        }

        return $testDirectoryKeys;
    }

    /**
     * @param string $testSubDirectoryKey
     * @param string $bundlePath
     *
     * @return string
     */
    protected function getTestSubDirectoryName($testSubDirectoryKey, $bundlePath)
    {
        $pathParts = [
            rtrim($bundlePath, DIRECTORY_SEPARATOR),
            static::BASE_TESTS_DIR,
            $testSubDirectoryKey,
        ];

        return $this->getDirectoryName($pathParts);
    }

    /**
     * @param array $pathParts
     *
     * @return string
     */
    protected function getDirectoryName(array $pathParts)
    {
        return implode($pathParts, DIRECTORY_SEPARATOR) . '/';
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function addAutoloadDevPsr0(array $composerJson)
    {
        if (!isset($composerJson[static::AUTOLOAD_DEV_KEY])) {
            $composerJson[static::AUTOLOAD_DEV_KEY] = [];
        }

        if (!isset($composerJson[static::AUTOLOAD_DEV_KEY]['psr-0'])) {
            $composerJson[static::AUTOLOAD_DEV_KEY]['psr-0'] = [];
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function addAutoloadDevPsr4(array $composerJson)
    {
        if (!isset($composerJson[static::AUTOLOAD_DEV_KEY])) {
            $composerJson[static::AUTOLOAD_DEV_KEY] = [];
        }

        if (!isset($composerJson[static::AUTOLOAD_DEV_KEY]['psr-4'])) {
            $composerJson[static::AUTOLOAD_DEV_KEY]['psr-4'] = [];
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $bundlePath
     *
     * @return array
     */
    protected function cleanupAutoload(array $composerJson, $bundlePath)
    {
        return $this->removeAutoloadInvalidEntries(
            $composerJson,
            [
                static::AUTOLOAD_KEY => [$this, 'removeAutoloadInvalidPaths'],
                static::AUTOLOAD_DEV_KEY => [$this, 'removeAutoloadInvalidNamespaces'],
            ],
            $bundlePath
        );
    }

    /**
     * @param array $composerJson
     * @param array $keys
     * @param string $bundlePath
     *
     * @return array
     */
    protected function removeAutoloadInvalidEntries(array $composerJson, array $keys, $bundlePath)
    {
        foreach ($keys as $keyToCheck => $callable) {
            if (isset($composerJson[$keyToCheck])) {
                foreach ($composerJson[$keyToCheck] as $key => $autoload) {
                    $composerJson[$keyToCheck][$key] = call_user_func($callable, $autoload, $bundlePath);
                    if (empty($composerJson[$keyToCheck][$key])) {
                        unset($composerJson[$keyToCheck][$key]);
                    }
                }

                if (empty($composerJson[$keyToCheck])) {
                    $composerJson[$keyToCheck] = new stdClass();
                }
            }
        }

        return $composerJson;
    }

    /**
     * @param array $autoload
     * @param string $bundlePath
     *
     * @return array
     */
    protected function removeAutoloadInvalidPaths(array $autoload, $bundlePath)
    {
        foreach ($autoload as $namespace => $relativeDirectory) {
            $pathParts = [
                rtrim($bundlePath, DIRECTORY_SEPARATOR),
                $relativeDirectory,
            ];

            if (!$this->pathExists($this->getDirectoryName($pathParts))) {
                unset($autoload[$namespace]);
            }
        }

        return $autoload;
    }

    /**
     * @param array $autoload
     * @param string $bundlePath
     *
     * @return array
     */
    protected function removeAutoloadInvalidNamespaces(array $autoload, $bundlePath)
    {
        foreach ($autoload as $namespace => $relativeDirectory) {
            if (substr($relativeDirectory, 0, 7) === 'vendor/') {
                continue;
            }

            $pathParts = [
                rtrim($bundlePath, DIRECTORY_SEPARATOR),
                static::BASE_TESTS_DIR,
                rtrim($namespace, '\\'),
            ];

            if (!$this->pathExists($this->getDirectoryName($pathParts))) {
                unset($autoload[$namespace]);
            }
        }

        return $autoload;
    }

}
