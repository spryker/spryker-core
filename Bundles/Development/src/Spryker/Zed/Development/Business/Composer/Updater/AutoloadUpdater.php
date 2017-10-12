<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\SplFileInfo;

class AutoloadUpdater implements UpdaterInterface
{

    const AUTOLOAD_KEY = 'autoload';
    const AUTOLOAD_DEV_KEY = 'autoload-dev';

    const BASE_TESTS_DIR = 'tests';
    const BASE_SRC_DIR = 'src';
    const BASE_SUPPORT_DIR = '_support';
    const BASE_HELPER_DIR = 'Helper';

    const SPRYKER_TEST_NAMESPACE = 'SprykerTest';
    const SPRYKER_NAMESPACE = 'Spryker';

    const PSR_0 = 'psr-0';
    const PSR_4 = 'psr-4';

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
     * @var array
     */
    protected $autoloadPSR4Whitelist = [
        'Spryker',
        'Helper',
    ];

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    public function update(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $composerJson = $this->updateAutoload($composerJson, $composerJsonFile);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param \Symfony\Component\Finder\SplFileInfo $composerJsonFile
     *
     * @return array
     */
    protected function updateAutoload(array $composerJson, SplFileInfo $composerJsonFile)
    {
        $bundlePath = dirname($composerJsonFile->getPathname());

        $composerJson = $this->updateAutoloadWithDefaultSrcDirectory($composerJson, $bundlePath);

        $composerJson = $this->updateAutoloadWithSupportTestClasses($composerJson, $bundlePath);

        $composerJson = $this->updateAutoloadDevWithDefaultTestDirectory($composerJson, $bundlePath);

        $testDirectoryKeys = $this->buildTestDirectoryKeys();
        foreach ($testDirectoryKeys as $testDirectoryKey) {
            $composerJson = $this->updateAutoloadDevForDeprecatedTestKeys($composerJson, $testDirectoryKey, $bundlePath);
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
    protected function updateAutoloadWithDefaultSrcDirectory(array $composerJson, $bundlePath)
    {
        $pathParts = [
            static::BASE_SRC_DIR,
            static::SPRYKER_NAMESPACE,
        ];
        $directoryPath = $this->getPath(array_merge([rtrim($bundlePath, DIRECTORY_SEPARATOR)], $pathParts));

        if ($this->pathExists($directoryPath)) {
            $composerJson = $this->addAutoloadPsr4($composerJson);
            $composerJson[static::AUTOLOAD_KEY][static::PSR_4][static::SPRYKER_NAMESPACE . '\\'] = $this->getPath($pathParts);
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $bundlePath
     *
     * @return array
     */
    protected function updateAutoloadWithSupportTestClasses(array $composerJson, $bundlePath)
    {
        $bundleName = $this->getLastPartOfPath($bundlePath);
        foreach ($this->applications as $application) {
            $pathParts = [
                static::BASE_TESTS_DIR,
                static::SPRYKER_TEST_NAMESPACE,
                $application,
                $bundleName,
                static::BASE_SUPPORT_DIR,
                static::BASE_HELPER_DIR,
            ];

            $directoryPath = $this->getPath(array_merge([rtrim($bundlePath, DIRECTORY_SEPARATOR)], $pathParts));

            if ($this->pathExists($directoryPath)) {
                $toAdd = false;
                $fileNames = scandir($directoryPath);

                foreach ($fileNames as $fileName) {
                    if (preg_match('/' . static::BASE_HELPER_DIR . '/', $fileName)) {
                        $toAdd = true;
                        break;
                    }
                }

                if ($toAdd) {
                    $composerJson = $this->addAutoloadPsr4($composerJson);
                    $composerJson[static::AUTOLOAD_KEY][static::PSR_4][static::SPRYKER_TEST_NAMESPACE . '\\' . $application . '\\' . $bundleName . '\\']
                        = $this->getPath($pathParts);
                }
            }
        }

        return $composerJson;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getLastPartOfPath($path)
    {
        $pathArray = explode('/', rtrim($path, '/'));
        return end($pathArray);
    }

    /**
     * @param array $composerJson
     * @param string $bundlePath
     *
     * @return array
     */
    protected function updateAutoloadDevWithDefaultTestDirectory(array $composerJson, $bundlePath)
    {
        $pathParts = [
            static::BASE_TESTS_DIR,
            static::SPRYKER_TEST_NAMESPACE,
        ];

        $directoryPath = $this->getPath(array_merge([rtrim($bundlePath, DIRECTORY_SEPARATOR)], $pathParts));

        if ($this->pathExists($directoryPath)) {
            $composerJson = $this->addAutoloadDevPsr4($composerJson);
            $composerJson[static::AUTOLOAD_DEV_KEY][static::PSR_4][static::SPRYKER_TEST_NAMESPACE . '\\'] = $this->getPath($pathParts);
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
    protected function updateAutoloadDevForDeprecatedTestKeys(array $composerJson, $testDirectoryKey, $bundlePath)
    {
        $directoryPath = $this->getPath(
            [
                rtrim($bundlePath, DIRECTORY_SEPARATOR),
                static::BASE_TESTS_DIR,
                $testDirectoryKey,
            ]
        );

        if ($this->pathExists($directoryPath)) {
            $composerJson = $this->addAutoloadDevPsr0($composerJson);
            $composerJson[static::AUTOLOAD_DEV_KEY][static::PSR_0][$testDirectoryKey] = static::BASE_TESTS_DIR . '/';
        }

        if (isset($composerJson[static::AUTOLOAD_KEY][static::PSR_0][$testDirectoryKey])) {
            unset($composerJson[static::AUTOLOAD_KEY][static::PSR_0][$testDirectoryKey]);
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
     * @param array $pathParts
     *
     * @return string
     */
    protected function getPath(array $pathParts)
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
        return $this->addKeyToComposer($composerJson, static::AUTOLOAD_DEV_KEY, static::PSR_0);
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function addAutoloadDevPsr4(array $composerJson)
    {
        return $this->addKeyToComposer($composerJson, static::AUTOLOAD_DEV_KEY, static::PSR_4);
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function addAutoloadPsr4(array $composerJson)
    {
        return $this->addKeyToComposer($composerJson, static::AUTOLOAD_KEY, static::PSR_4);
    }

    /**
     * @param array $composerJson
     * @param string $type
     * @param string $key
     *
     * @return array
     */
    protected function addKeyToComposer(array $composerJson, $type, $key)
    {
        if (!isset($composerJson[$type])) {
            $composerJson[$type] = [];
        }

        if (!isset($composerJson[$type][$key])) {
            $composerJson[$type][$key] = [];
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
        $composerJson = $this->removeInvalidAutoloadEntries(
            $composerJson,
            [
                static::AUTOLOAD_KEY => [$this, 'removeInvalidAutoloadPaths'],
                static::AUTOLOAD_DEV_KEY => [$this, 'removeInvalidAutoloadNamespaces'],
            ],
            $bundlePath
        );

        $composerJson = $this->removeAutoloadDuplicates($composerJson);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param array $keys
     * @param string $bundlePath
     *
     * @return array
     */
    protected function removeInvalidAutoloadEntries(array $composerJson, array $keys, $bundlePath)
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
                    unset($composerJson[$keyToCheck]);
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
    protected function removeInvalidAutoloadPaths(array $autoload, $bundlePath)
    {
        foreach ($autoload as $namespace => $relativeDirectory) {
            $path = $this->getPath([
                rtrim($bundlePath, DIRECTORY_SEPARATOR),
                $relativeDirectory,
            ]);

            if (!$this->pathExists($path) || !in_array($this->getLastPartOfPath($relativeDirectory), $this->autoloadPSR4Whitelist)) {
                if (is_file($path)) {
                    continue;
                }

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
    protected function removeInvalidAutoloadNamespaces(array $autoload, $bundlePath)
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

            if (!$this->pathExists($this->getPath($pathParts))) {
                unset($autoload[$namespace]);
            }
        }

        return $autoload;
    }

    /**
     * @param array $composerJson
     *
     * @return array
     */
    protected function removeAutoloadDuplicates(array $composerJson)
    {
        if (isset($composerJson[static::AUTOLOAD_KEY]) && is_array($composerJson[static::AUTOLOAD_KEY]) && $composerJson[static::AUTOLOAD_KEY][static::PSR_4]) {
            foreach ($composerJson[static::AUTOLOAD_KEY][static::PSR_4] as $key => $autoload) {
                $key = rtrim($key, '\\');
                if (isset($composerJson[static::AUTOLOAD_KEY][static::PSR_0][$key])) {
                    unset($composerJson[static::AUTOLOAD_KEY][static::PSR_0][$key]);
                }

                if (empty($composerJson[static::AUTOLOAD_KEY][static::PSR_0])) {
                    unset($composerJson[static::AUTOLOAD_KEY][static::PSR_0]);
                }
            }
        }

        return $composerJson;
    }

}
