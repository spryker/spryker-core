<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfig;
use Spryker\Shared\Kernel\Store;
use SprykerTest\Shared\Testify\Exception\StoreNotFoundException;

class Environment extends Module
{
    protected const TESTING_APPLICATION_ENV_NAME = 'devtest';

    protected const CONFIG_IS_ISOLATED_MODULE_TEST = 'isolated';

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_IS_ISOLATED_MODULE_TEST => false,
    ];

    /**
     * @var string|null
     */
    protected $rootDirectory;

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->prepareIsolatedModuleTests();

        $applicationEnv = $this->getApplicationEnvironment();

        defined('MODULE_UNDER_TEST_ROOT_DIR') || define('MODULE_UNDER_TEST_ROOT_DIR', $this->getModuleUnderTestRootDirectory());

        defined('APPLICATION_ENV') || define('APPLICATION_ENV', $applicationEnv);
        defined('APPLICATION') || define('APPLICATION', 'ZED');

        $rootDirectory = $this->getRootDirectory();
        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', rtrim($rootDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . 'src/');
        defined('APPLICATION_VENDOR_DIR') || define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . 'vendor/');

        $this->defineStore();

        defined('APPLICATION_CODE_BUCKET') || define('APPLICATION_CODE_BUCKET', $this->getCodeBucket());
        putenv('APPLICATION_CODE_BUCKET=' . APPLICATION_CODE_BUCKET);
    }

    /**
     * @return void
     */
    protected function prepareIsolatedModuleTests(): void
    {
        if ($this->config[static::CONFIG_IS_ISOLATED_MODULE_TEST]) {
            $this->createDefaultStoreFile();
            $this->createStoresFile();
        }
    }

    /**
     * @return void
     */
    protected function createDefaultStoreFile(): void
    {
        $defaultFileContent = sprintf('<?php return "%s";', $this->config['defaultStore'] ?? 'DE');
        $pathToFile = $this->getRootDirectory() . 'config/Shared/default_store.php';

        if (!file_exists(dirname($pathToFile))) {
            mkdir(dirname($pathToFile), 0777, true);
        }

        file_put_contents($pathToFile, $defaultFileContent);
    }

    /**
     * @return void
     */
    protected function createStoresFile(): void
    {
        $defaultStoreConfiguration = [
            'DE' => [
                'locales' => ['de' => 'de_DE'],
                'countries' => ['DE'],
                'currencyIsoCode' => 'EUR',
            ],
        ];

        $storeConfiguration = $this->config['stores'] ?? $defaultStoreConfiguration;

        $storesFileContent = sprintf('<?php return %s;', var_export($storeConfiguration, true));
        $pathToFile = $this->getRootDirectory() . 'config/Shared/stores.php';

        if (!file_exists(dirname($pathToFile))) {
            mkdir(dirname($pathToFile), 0777, true);
        }

        file_put_contents($pathToFile, $storesFileContent);
    }

    /**
     * @return string|null
     */
    protected function getModuleUnderTestRootDirectory(): ?string
    {
        if ($this->config[static::CONFIG_IS_ISOLATED_MODULE_TEST]) {
            return $this->buildModuleUnderTestRootDirectory();
        }

        return null;
    }

    /**
     * @return string
     */
    protected function buildModuleUnderTestRootDirectory(): string
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, Configuration::projectDir());
        $srcDirectoryPosition = array_search('tests', $pathParts);
        $rootDirPathParts = array_slice($pathParts, 1, $srcDirectoryPosition - 1);

        return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rootDirPathParts) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    protected function getRootDirectory(): string
    {
        if ($this->canBuildVirtualDirectory()) {
            return $this->buildVirtualDirectory();
        }

        if (!$this->rootDirectory) {
            $directory = getcwd();

            $pathParts = explode(DIRECTORY_SEPARATOR, Configuration::projectDir());
            $srcDirectoryPosition = array_search('current', $pathParts);

            $rootDirPathParts = array_slice($pathParts, 1, $srcDirectoryPosition);
            if ($rootDirPathParts) {
                $directory = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rootDirPathParts);
            }

            $this->rootDirectory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        return $this->rootDirectory;
    }

    /**
     * @return bool
     */
    protected function canBuildVirtualDirectory(): bool
    {
        return ($this->config[static::CONFIG_IS_ISOLATED_MODULE_TEST] && $this->hasModule('\\' . VirtualFilesystemHelper::class));
    }

    /**
     * @return string
     */
    protected function buildVirtualDirectory(): string
    {
        if (!$this->rootDirectory) {
            $this->rootDirectory = $this->getVirtualFilesystemHelper()->getVirtualDirectory();
        }

        return $this->rootDirectory;
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper
     */
    protected function getVirtualFilesystemHelper(): VirtualFilesystemHelper
    {
        /** @var \SprykerTest\Shared\Testify\Helper\VirtualFilesystemHelper $virtualDirectoryHelper */
        $virtualDirectoryHelper = $this->getModule('\\' . VirtualFilesystemHelper::class);

        return $virtualDirectoryHelper;
    }

    /**
     * @deprecated Dynamic stores are based on the Channel context.
     *
     * @throws \SprykerTest\Shared\Testify\Exception\StoreNotFoundException
     *
     * @return string
     */
    protected function getStore(): string
    {
        if (getenv('APPLICATION_STORE')) {
            return getenv('APPLICATION_STORE');
        }

        if (isset($_SERVER['APPLICATION_STORE'])) {
            return getenv('APPLICATION_STORE');
        }

        $defaultStore = $this->getDefaultStore();
        if ($defaultStore) {
            return $defaultStore;
        }

        $firstDefinedStore = $this->getFirstDefinedStore();
        if ($firstDefinedStore) {
            return $firstDefinedStore;
        }

        throw new StoreNotFoundException(
            'Could not find a defined store name. Please make sure that you have a "stores.php" and a "default_store.php" in the configuration directory "config/Shared/".'
        );
    }

    /**
     * @return string
     */
    protected function getCodeBucket(): string
    {
        return (new CodeBucketConfig())->getCurrentCodeBucket();
    }

    /**
     * @return string
     */
    protected function getApplicationEnvironment(): string
    {
        if (getenv('SPRYKER_TESTING_ENABLED')) {
            return getenv('APPLICATION_ENV');
        }

        return static::TESTING_APPLICATION_ENV_NAME;
    }

    /**
     * @return string|null
     */
    private function getDefaultStore(): ?string
    {
        $defaultStoreFile = $this->getRootDirectory() . '/config/Shared/default_store.php';

        if (file_exists($defaultStoreFile)) {
            return include $defaultStoreFile;
        }

        return null;
    }

    /**
     * @return string|null
     */
    private function getFirstDefinedStore(): ?string
    {
        $storesFile = $this->getRootDirectory() . '/config/Shared/stores.php';

        if (file_exists($storesFile)) {
            $stores = include $storesFile;

            return current(array_keys($stores));
        }

        return null;
    }

    /**
     * @return void
     */
    protected function defineStore(): void
    {
        if (!Store::isDynamicStoreMode()) {
            $store = $this->getStore();
            defined('APPLICATION_STORE') || define('APPLICATION_STORE', $store);
            putenv('APPLICATION_STORE=' . $store);
        }
    }
}
