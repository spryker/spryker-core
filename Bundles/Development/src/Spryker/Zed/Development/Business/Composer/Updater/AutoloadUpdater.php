<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer\Updater;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\DashToCamelCase;

class AutoloadUpdater implements UpdaterInterface
{
    public const AUTOLOAD_KEY = 'autoload';
    public const AUTOLOAD_DEV_KEY = 'autoload-dev';

    public const BASE_TESTS_DIRECTORY = 'tests';
    public const BASE_SRC_DIRECTORY = 'src';
    public const BASE_SUPPORT_DIRECTORY = '_support';
    public const BASE_HELPER_DIRECTORY = 'Helper';
    protected const BASE_PAGE_OBJECT_DIRECTORY = 'PageObject';
    public const BASE_TESTER_DIRECTORY = 'Tester';
    public const BASE_FIXTURES_DIRECTORY = 'Fixtures';
    public const BASE_STEP_OVERRIDE_DIRECTORY = 'StepOverride';
    public const BASE_FILTER_DIRECTORY = 'Filter';

    public const SPRYKER_TEST_NAMESPACE = 'SprykerTest';
    public const SPRYKER_SDK_TEST_NAMESPACE = 'SprykerSdkTest';
    public const SPRYKER_ECO_TEST_NAMESPACE = 'SprykerEcoTest';
    public const SPRYKER_SHOP_TEST_NAMESPACE = 'SprykerShopTest';
    public const SPRYKER_MERCHANT_PORTAL_SHOP_TEST_NAMESPACE = 'SprykerMerchantPortalTest';

    public const SPRYKER_NAMESPACE = 'Spryker';
    public const SPRYKER_SHOP_NAMESPACE = 'SprykerShop';
    public const SPRYKER_ECO_NAMESPACE = 'SprykerEco';
    public const SPRYKER_SDK_NAMESPACE = 'SprykerSdk';
    public const SPRYKER_MERCHANT_PORTAL_NAMESPACE = 'SprykerMerchantPortal';

    public const PSR_0 = 'psr-0';
    public const PSR_4 = 'psr-4';

    protected const RESERVED_NAMESPACES = [
        'vendor/',
        'tests/_',
    ];

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
        self::SPRYKER_NAMESPACE,
        self::SPRYKER_SHOP_NAMESPACE,
        self::SPRYKER_ECO_NAMESPACE,
        self::BASE_HELPER_DIRECTORY,
        self::BASE_PAGE_OBJECT_DIRECTORY,
        self::BASE_TESTER_DIRECTORY,
        self::BASE_STEP_OVERRIDE_DIRECTORY,
        self::BASE_FIXTURES_DIRECTORY,
        self::BASE_FILTER_DIRECTORY,
        self::SPRYKER_SDK_NAMESPACE,
        self::SPRYKER_MERCHANT_PORTAL_NAMESPACE,
    ];

    /**
     * @var array
     */
    protected $sprykerCodeNamespaces = [
        self::SPRYKER_NAMESPACE,
        self::SPRYKER_SHOP_NAMESPACE,
        self::SPRYKER_ECO_NAMESPACE,
        self::SPRYKER_SDK_NAMESPACE,
        self::SPRYKER_MERCHANT_PORTAL_NAMESPACE,
    ];

    /**
     * @var array
     */
    protected $sprykerCodeTestNamespacesMapping = [
        self::SPRYKER_TEST_NAMESPACE,
        self::SPRYKER_SHOP_TEST_NAMESPACE,
        self::SPRYKER_ECO_TEST_NAMESPACE,
        self::SPRYKER_SDK_TEST_NAMESPACE,
        self::SPRYKER_MERCHANT_PORTAL_SHOP_TEST_NAMESPACE,
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
        $modulePath = dirname($composerJsonFile->getPathname());

        $composerJson = $this->updateAutoloadWithDefaultSrcDirectory($composerJson, $modulePath);

        $composerJson = $this->updateAutoloadWithSupportTestClasses($composerJson, $modulePath);

        $composerJson = $this->updateAutoloadDevWithDefaultTestDirectory($composerJson, $modulePath);

        $testDirectoryKeys = $this->buildTestDirectoryKeys();
        foreach ($testDirectoryKeys as $testDirectoryKey) {
            $composerJson = $this->updateAutoloadDevForDeprecatedTestKeys($composerJson, $testDirectoryKey, $modulePath);
        }

        $composerJson = $this->cleanupAutoload($composerJson, $modulePath);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $modulePath
     *
     * @return array
     */
    protected function updateAutoloadWithDefaultSrcDirectory(array $composerJson, $modulePath)
    {
        foreach ($this->sprykerCodeNamespaces as $sprykerCodeNamespace) {
            $pathParts = [
                static::BASE_SRC_DIRECTORY,
                $sprykerCodeNamespace,
            ];

            $directoryPath = $this->getPath(array_merge([rtrim($modulePath, DIRECTORY_SEPARATOR)], $pathParts));

            if ($this->pathExists($directoryPath)) {
                $composerJson = $this->addAutoloadPsr4($composerJson);
                $composerJson[static::AUTOLOAD_KEY][static::PSR_4][$sprykerCodeNamespace . '\\'] = $this->getPath($pathParts);
            }
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $modulePath
     *
     * @return array
     */
    protected function updateAutoloadWithSupportTestClasses(array $composerJson, $modulePath)
    {
        $moduleName = $this->getLastPartOfPath($modulePath);
        foreach ($this->sprykerCodeTestNamespacesMapping as $testNamespace) {
            foreach ($this->applications as $application) {
                $pathParts = [
                    static::BASE_TESTS_DIRECTORY,
                    $testNamespace,
                    $application,
                    $this->convertDashToCamelCase($moduleName),
                    static::BASE_SUPPORT_DIRECTORY,
                ];

                $supportDirectoryPath = $this->getPath(array_merge([rtrim($modulePath, DIRECTORY_SEPARATOR)], $pathParts));

                if ($this->pathExists($supportDirectoryPath)) {
                    $nonEmptySupportDirectories = $this->getNonEmptyDirectoriesWithHelpers($supportDirectoryPath);
                    $composerJson = $this->addAutoloadPsr4($composerJson);
                    foreach ($nonEmptySupportDirectories as $directory) {
                        preg_match('/' . static::BASE_SUPPORT_DIRECTORY . '\/(.+)/', $directory, $subNameSpace);
                        $composerJson[static::AUTOLOAD_KEY][static::PSR_4][$testNamespace . '\\' . $application . '\\' . $this->convertDashToCamelCase($moduleName) . '\\' . str_replace('/', '\\', $subNameSpace[1]) . '\\']
                            = $this->getPath(array_merge($pathParts, explode(DIRECTORY_SEPARATOR, $subNameSpace[1])));
                    }
                }
            }
        }

        return $composerJson;
    }

    /**
     * @param string $directory
     *
     * @return array
     */
    protected function getNonEmptyDirectoriesWithHelpers($directory)
    {
        $files = (new Finder())->files()->in($directory)->name('/Helper.php$/');
        $directories = [];
        foreach ($files as $file) {
            $directoryName = dirname(str_replace('//', '/', $file));
            if (!in_array($directoryName, $directories)) {
                $directories[] = $directoryName;
            }
        }

        return $directories;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getLastPartOfPath($path)
    {
        $pathArray = explode(DIRECTORY_SEPARATOR, rtrim($path, DIRECTORY_SEPARATOR));

        return end($pathArray);
    }

    /**
     * @param array $composerJson
     * @param string $modulePath
     *
     * @return array
     */
    protected function updateAutoloadDevWithDefaultTestDirectory(array $composerJson, $modulePath)
    {
        foreach ($this->sprykerCodeTestNamespacesMapping as $testNamespace) {
            $pathParts = [
                static::BASE_TESTS_DIRECTORY,
                $testNamespace,
            ];

            $directoryPath = $this->getPath(array_merge([rtrim($modulePath, DIRECTORY_SEPARATOR)], $pathParts));

            if ($this->pathExists($directoryPath)) {
                $composerJson = $this->addAutoloadDevPsr4($composerJson);
                $composerJson[static::AUTOLOAD_DEV_KEY][static::PSR_4][$testNamespace . '\\'] = $this->getPath($pathParts);
            }
        }

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param string $testDirectoryKey
     * @param string $modulePath
     *
     * @return array
     */
    protected function updateAutoloadDevForDeprecatedTestKeys(array $composerJson, $testDirectoryKey, $modulePath)
    {
        $directoryPath = $this->getPath(
            [
                rtrim($modulePath, DIRECTORY_SEPARATOR),
                static::BASE_TESTS_DIRECTORY,
                $testDirectoryKey,
            ]
        );

        if ($this->pathExists($directoryPath)) {
            $composerJson = $this->addAutoloadDevPsr0($composerJson);
            $composerJson[static::AUTOLOAD_DEV_KEY][static::PSR_0][$testDirectoryKey] = static::BASE_TESTS_DIRECTORY . DIRECTORY_SEPARATOR;
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
        return (is_dir($path) || $this->isFile($path));
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isFile($path)
    {
        return is_file(rtrim($path, DIRECTORY_SEPARATOR));
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
        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
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
     * @param string $modulePath
     *
     * @return array
     */
    protected function cleanupAutoload(array $composerJson, $modulePath)
    {
        $composerJson = $this->removeInvalidAutoloadEntries(
            $composerJson,
            [
                static::AUTOLOAD_KEY => [$this, 'removeInvalidAutoloadPaths'],
                static::AUTOLOAD_DEV_KEY => [$this, 'removeInvalidAutoloadNamespaces'],
            ],
            $modulePath
        );

        $composerJson = $this->removeAutoloadDuplicates($composerJson);

        return $composerJson;
    }

    /**
     * @param array $composerJson
     * @param array $keys
     * @param string $modulePath
     *
     * @return array
     */
    protected function removeInvalidAutoloadEntries(array $composerJson, array $keys, $modulePath)
    {
        foreach ($keys as $keyToCheck => $callable) {
            if (isset($composerJson[$keyToCheck])) {
                foreach ($composerJson[$keyToCheck] as $key => $autoload) {
                    $composerJson[$keyToCheck][$key] = call_user_func($callable, $autoload, $modulePath);
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
     * @param string $modulePath
     *
     * @return array
     */
    protected function removeInvalidAutoloadPaths(array $autoload, $modulePath)
    {
        foreach ($autoload as $namespace => $relativeDirectory) {
            $path = $this->getPath([
                rtrim($modulePath, DIRECTORY_SEPARATOR),
                $relativeDirectory,
            ]);

            if (!$this->pathExists($path) || !in_array($this->getLastPartOfPath($relativeDirectory), $this->autoloadPSR4Whitelist)) {
                if ($this->isFile($path)) {
                    continue;
                }

                unset($autoload[$namespace]);
            }
        }

        return $autoload;
    }

    /**
     * @param array $autoload
     * @param string $modulePath
     *
     * @return array
     */
    protected function removeInvalidAutoloadNamespaces(array $autoload, $modulePath)
    {
        foreach ($autoload as $namespace => $relativeDirectory) {
            if ($this->isReservedNamespace($relativeDirectory)) {
                continue;
            }

            $pathParts = [
                rtrim($modulePath, DIRECTORY_SEPARATOR),
                static::BASE_TESTS_DIRECTORY,
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

    /**
     * @param string $value
     *
     * @return string
     */
    protected function convertDashToCamelCase(string $value): string
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return $filterChain->filter($value);
    }

    /**
     * @param string $relativeDirectory
     *
     * @return bool
     */
    protected function isReservedNamespace(string $relativeDirectory): bool
    {
        foreach (static::RESERVED_NAMESPACES as $reservedNamespace) {
            if (strpos($relativeDirectory, $reservedNamespace) === 0) {
                return true;
            }
        }

        return false;
    }
}
