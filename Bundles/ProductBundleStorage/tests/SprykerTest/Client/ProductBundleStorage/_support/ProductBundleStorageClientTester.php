<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductBundleStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductForProductBundleStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageDependencyProvider;
use Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory;

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
 * @method \Spryker\Client\ProductBundleStorage\ProductBundleStorageClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductBundleStorageClientTester extends Actor
{
    use _generated\ProductBundleStorageClientTesterActions;

    /**
     * @var string
     */
    protected const EXTERNAL_URL_SMALL = 'small';

    /**
     * @var string
     */
    protected const PRODUCT_URL = '/en/product-1';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_NAME = 'Test Product';

    /**
     * @var int
     */
    protected const ID_PRODUCT_CONCRETE = 1;

    /**
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function createProductViewTransfer(): ProductViewTransfer
    {
        return (new ProductViewTransfer())
            ->setIdProductConcrete(static::ID_PRODUCT_CONCRETE)
            ->setName(static::TEST_PRODUCT_NAME)
            ->addImage($this->createProductImageStorageTransferWithExternalUrlSmall())
            ->setUrl(static::PRODUCT_URL);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductBundleStorageTransfer
     */
    public function createProductBundleStorageTransfer(): ProductBundleStorageTransfer
    {
        return (new ProductBundleStorageTransfer())
            ->addBundledProduct($this->createProductForProductBundleStorageTransfer());
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductBundleStorage\ProductBundleStorageFactory $productBundleStorageFactoryMock
     *
     * @return \Spryker\Client\CategoryStorage\CategoryStorageClientInterface
     */
    public function getClientMock(ProductBundleStorageFactory $productBundleStorageFactoryMock): ProductBundleStorageClientInterface
    {
        $container = new Container();
        (new ProductBundleStorageDependencyProvider())
            ->provideServiceLayerDependencies($container);

        $productBundleStorageFactoryMock->setContainer($container);

        $productBundleStorageClient = $this->getClient();
        $productBundleStorageClient->setFactory($productBundleStorageFactoryMock);

        return $productBundleStorageClient;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductForProductBundleStorageTransfer
     */
    protected function createProductForProductBundleStorageTransfer(): ProductForProductBundleStorageTransfer
    {
        return (new ProductForProductBundleStorageTransfer())
            ->setIdProductConcrete(static::ID_PRODUCT_CONCRETE);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageStorageTransfer
     */
    protected function createProductImageStorageTransferWithExternalUrlSmall(): ProductImageStorageTransfer
    {
        return (new ProductImageStorageTransfer())
            ->setExternalUrlSmall(static::EXTERNAL_URL_SMALL);
    }
}
