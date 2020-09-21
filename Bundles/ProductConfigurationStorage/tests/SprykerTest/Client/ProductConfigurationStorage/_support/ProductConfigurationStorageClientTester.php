<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationStorage;

use Codeception\Actor;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageDependencyProvider;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductConfigurationStorageClientTester extends Actor
{
    use _generated\ProductConfigurationStorageClientTesterActions;

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageFactory $productConfigurationStorageFactoryMock
     *
     * @return \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClientInterface
     */
    public function getClientMock(MockObject $productConfigurationStorageFactoryMock): ProductConfigurationStorageClientInterface
    {
        $container = new Container();
        $productConfigurationStorageDependencyProvider = new ProductConfigurationStorageDependencyProvider();
        $productConfigurationStorageDependencyProvider->provideServiceLayerDependencies($container);

        $productConfigurationStorageFactoryMock->setContainer($container);

        /** @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageClient $productConfigurationStorageClient */
        $productConfigurationStorageClient = $this->getLocator()->productConfigurationStorage()->client();
        $productConfigurationStorageClient->setFactory($productConfigurationStorageFactoryMock);

        return $productConfigurationStorageClient;
    }
}
