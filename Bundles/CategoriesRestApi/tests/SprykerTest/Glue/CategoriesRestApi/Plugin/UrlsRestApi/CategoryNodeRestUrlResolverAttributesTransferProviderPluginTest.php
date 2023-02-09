<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesRestApi\Plugin\UrlsRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToStoreClientInterface;
use Spryker\Glue\CategoriesRestApi\Plugin\UrlsRestApi\CategoryNodeRestUrlResolverAttributesTransferProviderPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Glue\CategoriesRestApi\CategoriesRestApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CategoriesRestApi
 * @group Plugin
 * @group UrlsRestApi
 * @group CategoryNodeRestUrlResolverAttributesTransferProviderPluginTest
 * Add your own group annotations below this line
 */
class CategoryNodeRestUrlResolverAttributesTransferProviderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_CATEGORY = 1;

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const LOCALE_NAME = 'de_DE';

    /**
     * @var int
     */
    protected const ID_LOCALE = 2;

    /**
     * @var int
     */
    protected const ID_RESOURCE_CATEGORYNODE = 3;

    /**
     * @var \SprykerTest\Glue\CategoriesRestApi\CategoriesRestApiTester
     */
    protected CategoriesRestApiTester $tester;

    /**
     * @return void
     */
    public function testProvideRestUrlResolverAttributesTransferByUrlStorageTransferShouldReturnTransferWhenCategoryAssignedToTheStore(): void
    {
        // Arrange
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin = new CategoryNodeRestUrlResolverAttributesTransferProviderPlugin();

        $this->mockCategoryStorageClient(static::ID_CATEGORY);
        $this->mockStoreClient(static::STORE_NAME);
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->setFactory($this->tester->getFactory());

        $urlStorageTransfer = $this->createUrlStorageTransfer(static::ID_LOCALE, static::ID_RESOURCE_CATEGORYNODE, static::LOCALE_NAME);

        // Act
        $restUrlResolverAttributesTransfer = $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);

        // Assert
        $this->assertNotNull($restUrlResolverAttributesTransfer);
        $this->assertSame(static::ID_RESOURCE_CATEGORYNODE, (int)$restUrlResolverAttributesTransfer->getEntityId());
    }

    /**
     * @return void
     */
    public function testProvideRestUrlResolverAttributesTransferByUrlStorageTransferShouldReturnTransferWhenCategoryAssignedToTheStoreAndStoreNameProvidedInLocales(): void
    {
        // Arrange
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin = new CategoryNodeRestUrlResolverAttributesTransferProviderPlugin();

        $this->mockCategoryStorageClient(static::ID_CATEGORY);
        $this->mockStoreClient(static::STORE_NAME);
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->setFactory($this->tester->getFactory());

        $urlStorageTransfer = $this->createUrlStorageTransfer(static::ID_LOCALE, static::ID_RESOURCE_CATEGORYNODE);
        $urlStorageTransfer->addUrlStorage(
            (new UrlStorageTransfer())->setFkLocale(static::ID_LOCALE)->setLocaleName(static::LOCALE_NAME),
        );

        // Act
        $restUrlResolverAttributesTransfer = $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);

        // Assert
        $this->assertNotNull($restUrlResolverAttributesTransfer);
        $this->assertSame(static::ID_RESOURCE_CATEGORYNODE, (int)$restUrlResolverAttributesTransfer->getEntityId());
    }

    /**
     * @return void
     */
    public function testProvideRestUrlResolverAttributesTransferByUrlStorageTransferShouldReturnNullWhenCategoryNotAssignedToTheStore(): void
    {
        // Assert
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin = new CategoryNodeRestUrlResolverAttributesTransferProviderPlugin();

        $this->mockCategoryStorageClient();
        $this->mockStoreClient(static::STORE_NAME);
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->setFactory($this->tester->getFactory());

        $urlStorageTransfer = $this->createUrlStorageTransfer(static::ID_LOCALE, static::ID_RESOURCE_CATEGORYNODE, static::LOCALE_NAME);

        // Act
        $restUrlResolverAttributesTransfer = $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);

        // Assert
        $this->assertNull($restUrlResolverAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testProvideRestUrlResolverAttributesTransferByUrlStorageTransferShouldReturnExceptionWhenRequiredTransferPropertyIsMissing(): void
    {
        // Assert
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin = new CategoryNodeRestUrlResolverAttributesTransferProviderPlugin();

        $this->mockCategoryStorageClient();
        $this->mockStoreClient(static::STORE_NAME);
        $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->setFactory($this->tester->getFactory());

        $urlStorageTransfer = $this->createUrlStorageTransfer(static::ID_LOCALE, null, static::LOCALE_NAME);
        $this->expectException(NullValueException::class);

        // Act
        $restUrlResolverAttributesTransfer = $categoryNodeRestUrlResolverAttributesTransferProviderPlugin->provideRestUrlResolverAttributesTransferByUrlStorageTransfer($urlStorageTransfer);
    }

    /**
     * @param int|null $idCategory
     *
     * @return void
     */
    protected function mockCategoryStorageClient(?int $idCategory = null): void
    {
        $categoryStorageClientMock = $this->createMock(
            CategoriesRestApiToCategoryStorageClientInterface::class,
        );

        $categoryStorageClientMock
            ->method('getCategoryNodeById')
            ->willReturn((new CategoryNodeStorageTransfer())->setIdCategory($idCategory));

        $this->tester->mockFactoryMethod('getCategoryStorageClient', $categoryStorageClientMock);
    }

    /**
     * @param string $storeName
     *
     * @return void
     */
    protected function mockStoreClient(string $storeName): void
    {
        $storeClientMock = $this->createMock(
            CategoriesRestApiToStoreClientInterface::class,
        );

        $storeClientMock
            ->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($storeName));

        $this->tester->mockFactoryMethod('getStoreClient', $storeClientMock);
    }

    /**
     * @param int $idLocale
     * @param int|null $idResourceCategorynode
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer
     */
    protected function createUrlStorageTransfer(int $idLocale, ?int $idResourceCategorynode = null, ?string $localeName = null): UrlStorageTransfer
    {
        return (new UrlStorageTransfer())
            ->setFkLocale($idLocale)
            ->setFkResourceCategorynode($idResourceCategorynode)
            ->setLocaleName($localeName);
    }
}
