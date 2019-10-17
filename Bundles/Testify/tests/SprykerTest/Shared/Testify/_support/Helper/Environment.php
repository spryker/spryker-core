<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use SprykerTest\Shared\Testify\Exception\StoreNotFoundException;

class Environment extends Module
{
    protected const TESTING_APPLICATION_ENV_NAME = 'devtest';

    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * @return void
     */
    public function _initialize()
    {
        $rootDirectory = $this->getRootDirectory();
        $store = $this->getStore();
        $applicationEnv = $this->getApplicationEnvironment();

        defined('APPLICATION_ENV') || define('APPLICATION_ENV', $applicationEnv);
        defined('APPLICATION_STORE') || define('APPLICATION_STORE', $store);
        putenv('APPLICATION_STORE=' . $store);
        defined('APPLICATION') || define('APPLICATION', 'ZED');

        defined('APPLICATION_ROOT_DIR') || define('APPLICATION_ROOT_DIR', $rootDirectory);
        defined('APPLICATION_SOURCE_DIR') || define('APPLICATION_SOURCE_DIR', APPLICATION_ROOT_DIR . '/src');
        defined('APPLICATION_VENDOR_DIR') || define('APPLICATION_VENDOR_DIR', APPLICATION_ROOT_DIR . '/vendor');
    }

    /**
     * @return string
     */
    protected function getRootDirectory(): string
    {
        if (!$this->rootDirectory) {
            $pathParts = explode(DIRECTORY_SEPARATOR, Configuration::projectDir());
            $srcDirectoryPosition = array_search('current', $pathParts);

            $rootDirPathParts = array_slice($pathParts, 1, $srcDirectoryPosition);
            if ($rootDirPathParts) {
                $this->rootDirectory = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rootDirPathParts);
            } else {
                $this->rootDirectory = getcwd();
            }
        }

        return $this->rootDirectory;
    }

    /**
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
}
