<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiDependencyProvider;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToStoreClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CategoriesRestApi
 * @group CategoriesRestApiResourceTest
 * Add your own group annotations below this line
 */
class CategoriesRestApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     *
     * @var string
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @uses \Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES
     *
     * @var string
     */
    protected const RESOURCE_CATEGORY_NODES = 'category-nodes';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Glue\CategoriesRestApi\CategoriesRestApiTester
     */
    protected CategoriesRestApiTester $tester;

    /**
     * @var \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface
     */
    protected CategoriesRestApiResourceInterface $categoriesRestApiResource;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->tester->getContainer()->set(
            static::SERVICE_RESOURCE_BUILDER,
            new RestResourceBuilder(),
        );
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->categoriesRestApiResource = $this->tester->getLocator()->categoriesRestApi()->resource();
        $this->tester->setDependency(CategoriesRestApiDependencyProvider::CLIENT_STORE, $this->getStoreClientMock());
    }

    /**
     * @return void
     */
    public function testFindCategoryNodeByIdShouldReturnRestResourceOfTypeCategoryNodes(): void
    {
        // Arrange
        $categoryNodeStorageTransfer = (new CategoryNodeStorageTransfer())->setNodeId(1)->setIdCategory(1);
        $categoryStorageClientMock = $this->getCategoryStorageClientMock();
        $categoryStorageClientMock->method('getCategoryNodeById')->willReturn($categoryNodeStorageTransfer);

        // Act
        $restResource = $this->categoriesRestApiResource->findCategoryNodeById(1, static::STORE_NAME);

        // Assert
        $this->assertInstanceOf(RestResourceInterface::class, $restResource);
        $this->assertSame('1', $restResource->getId());
        $this->assertSame(static::RESOURCE_CATEGORY_NODES, $restResource->getType());
    }

    /**
     * @return void
     */
    public function testFindCategoryNodeByIdShouldReturnEmptyRestResource(): void
    {
        // Arrange
        $categoryStorageClientMock = $this->getCategoryStorageClientMock();
        $categoryStorageClientMock->method('getCategoryNodeById')->willReturn(new CategoryNodeStorageTransfer());

        // Act
        $restResource = $this->categoriesRestApiResource->findCategoryNodeById(2, static::STORE_NAME);

        // Assert
        $this->assertNull($restResource);
    }

    /**
     * @return void
     */
    public function testFindCategoryNodeByIdsShouldReturnTwoFoundCategoryNodesIndexedByNodeId(): void
    {
        // Arrange
        $categoryStorageClientMock = $this->getCategoryStorageClientMock();
        $categoryStorageClientMock->method('getCategoryNodeByIds')->willReturn([
            (new CategoryNodeStorageTransfer())->setIdCategory(1)->setNodeId(1),
            (new CategoryNodeStorageTransfer())->setIdCategory(2)->setNodeId(3),
        ]);

        // Act
        $restResource = $this->categoriesRestApiResource->findCategoryNodeByIds([1, 3], static::STORE_NAME);

        // Assert
        $this->assertCount(2, $restResource);
        $this->assertArrayHasKey(1, $restResource);
        $this->assertArrayHasKey(3, $restResource);
    }

    /**
     * @return void
     */
    public function testFindCategoryNodeByIdsShouldReturnEmptyRestResource(): void
    {
        // Arrange
        $categoryStorageClientMock = $this->getCategoryStorageClientMock();
        $categoryStorageClientMock->method('getCategoryNodeByIds')->willReturn([]);

        // Act
        $restResource = $this->categoriesRestApiResource->findCategoryNodeByIds([1, 3], static::STORE_NAME);

        // Assert
        $this->assertCount(0, $restResource);
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToStoreClientInterface
     */
    protected function getStoreClientMock(): CategoriesRestApiToStoreClientInterface
    {
        $storeTransfer = (new StoreTransfer())->setName(static::STORE_NAME);
        $storeClientMock = $this->getMockBuilder(CategoriesRestApiToStoreClientInterface::class)->getMock();
        $storeClientMock->method('getCurrentStore')->willReturn($storeTransfer);

        return $storeClientMock;
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface
     */
    protected function getCategoryStorageClientMock(): CategoriesRestApiToCategoryStorageClientInterface
    {
        $categoryStorageClientMock = $this->getMockBuilder(CategoriesRestApiToCategoryStorageClientInterface::class)->getMock();
        $this->tester->setDependency(CategoriesRestApiDependencyProvider::CLIENT_CATEGORY_STORAGE, $categoryStorageClientMock);

        return $categoryStorageClientMock;
    }
}
