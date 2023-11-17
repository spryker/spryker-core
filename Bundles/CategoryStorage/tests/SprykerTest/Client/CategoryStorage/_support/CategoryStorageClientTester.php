<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CategoryStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Client\CategoryStorage\CategoryStorageConfig;
use Spryker\Client\CategoryStorage\CategoryStorageDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Store\StoreDependencyProvider as ClientStoreDependencyProvider;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Client\CategoryStorage\PHPMD)
 */
class CategoryStorageClientTester extends Actor
{
    use _generated\CategoryStorageClientTesterActions;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @return void
     */
    public function addDependencies(): void
    {
        $this->setDependency(ClientStoreDependencyProvider::PLUGINS_STORE_EXPANDER, [
            $this->createStoreStorageStoreExpanderPluginMock(),
        ]);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\CategoryStorage\CategoryStorageFactory $categoryStorageFactoryMock
     *
     * @return \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    public function getClientMock(MockObject $categoryStorageFactoryMock): CategoryStorageClientInterface
    {
        $categoryStorageFactoryMock
            ->method('getConfig')
            ->willReturn(new CategoryStorageConfig());

        $container = new Container();
        $categoryStorageDependencyProvider = new CategoryStorageDependencyProvider();
        $categoryStorageDependencyProvider->provideServiceLayerDependencies($container);

        $categoryStorageFactoryMock->setContainer($container);

        /** @var \Spryker\Client\CategoryStorage\CategoryStorageClient $categoryStorageClient */
        $categoryStorageClient = $this->getClient();
        $categoryStorageClient->setFactory($categoryStorageFactoryMock);

        return $categoryStorageClient;
    }

    /**
     * @return \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    public function getClient(): CategoryStorageClientInterface
    {
        return $this->getLocator()->categoryStorage()->client();
    }

    /**
     * @return \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeTransfer = (new StoreTransfer())
            ->setName(static::DEFAULT_STORE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY);

        $storeStorageStoreExpanderPluginMock = Stub::makeEmpty(StoreExpanderPluginInterface::class, [
            'expand' => $storeTransfer,
        ]);

        return $storeStorageStoreExpanderPluginMock;
    }
}
