<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Helper;

use Codeception\TestInterface;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\Storage\StorageFactory;
use SprykerTest\Client\Storage\Redis\ServiceTest;
use SprykerTest\Client\Testify\Helper\ClientHelperTrait;
use SprykerTest\Client\Testify\Helper\DependencyProviderHelperTrait;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;

class StorageHelper extends AbstractHelper
{
    use ClientHelperTrait;
    use LocatorHelperTrait;
    use DependencyHelperTrait;
    use DependencyProviderHelperTrait;
    use StaticVariablesHelper;

    /**
     * @var \SprykerTest\Client\Storage\Helper\InMemoryStoragePluginInterface|null
     */
    protected $inMemoryStoragePlugin;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * We need to disable the auto-wiring for an obsolete test { @link ServiceTest }
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanupInMemoryStorage();
        $this->cleanupStorageState();

        // Do not initialize for this partially outdated test class
        if ($test instanceof ServiceTest) {
            return;
        }

        $this->initializeClient();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetStaticCaches();
    }

    /**
     * @return void
     */
    protected function cleanupStorageState()
    {
        $this->cleanupStaticCache(StorageClient::class, 'service', null);
        $this->cleanupStaticCache(StorageFactory::class, 'storageService', null);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function assertStorageHasKey(string $key): void
    {
        $allKeys = $this->getInMemoryStoragePlugin()->getAllKeys();

        $this->assertTrue(in_array($key, $allKeys), $this->format(sprintf(
            '<fg=yellow>%s</> not found in the storage but was expected to be there.',
            $key,
        )));
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function assertStorageNotHasKey(string $key): void
    {
        $allKeys = $this->getInMemoryStoragePlugin()->getAllKeys();

        $this->assertFalse(in_array($key, $allKeys), $this->format(sprintf(
            '<fg=yellow>%s</> found in the storage but was not expected to be there.',
            $key,
        )));
    }

    /**
     * This will clean-up the in-memory storage.
     *
     * @return void
     */
    public function cleanupInMemoryStorage(): void
    {
        $inMemoryStorage = $this->getInMemoryStoragePlugin();
        $inMemoryStorage->deleteAll();
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->storageClient;
    }

    /**
     * @param string $key
     * @param string $value
     * @param int|null $ttl
     *
     * @return void
     */
    public function mockStorageData(string $key, string $value, ?int $ttl = null): void
    {
        $this->getInMemoryStoragePlugin()->set($key, $value, $ttl);
    }

    /**
     * @return \SprykerTest\Client\Storage\Helper\InMemoryStoragePluginInterface
     */
    public function getInMemoryStoragePlugin(): InMemoryStoragePluginInterface
    {
        if ($this->inMemoryStoragePlugin === null) {
            $this->inMemoryStoragePlugin = new InMemoryStoragePlugin();
        }

        return $this->inMemoryStoragePlugin;
    }

    /**
     * Creates a StorageClient with an in-memory (Storage) PluginInterface and ensures that the locator also returns this mock
     * when used with `$locator->storage()->client()`.
     *
     * @return void
     */
    protected function initializeClient(): void
    {
        $this->getDependencyProviderHelper()->setDependency(
            StorageDependencyProvider::PLUGIN_STORAGE,
            $this->getInMemoryStoragePlugin(),
        );

        // Resolves the StorageClientInterface with all mocks from above.
        /** @var \Spryker\Client\Storage\StorageClientInterface $storageClient */
        $storageClient = $this->getClientHelper()->getClient('Storage');
        $this->storageClient = $storageClient;

        // Ensure `$locator->storage()->client()` returns always the StorageClientInterface with all mocks from above.
        $this->getLocatorHelper()->addToLocatorCache('storage-client', $this->storageClient);
    }
}
