<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductBundleCollectionTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeBridge;

/**
 * Auto-generated group annotations
 * @group Spryker
 * @group Zed
 * @group ProductBundleProductListConnector
 * @group Business
 * @group ProductBundleProductListConnectorFacade
 * @group BlacklistExpandProductBundleTest
 * Add your own group annotations below this line
 */
class BlacklistExpandProductBundleTest extends Unit
{
    protected const PRODUCT_ID_1 = 1;

    protected const BUNDLE_PRODUCT_ID = 20;

    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_BLACKLIST
     */
    protected const PRODUCT_LIST_TYPE_BLACKLIST = 'blacklist';

    /**
     * @var \SprykerTest\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleBlacklistShouldAddBundle(): void
    {
        //Assign
        $productBundleCollection = $this->createProductBundleCollectionTransfer(static::BUNDLE_PRODUCT_ID);
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createBlacklistProductListTransfer([static::PRODUCT_ID_1]);
        $expectedProductIds = [
            static::PRODUCT_ID_1,
            static::BUNDLE_PRODUCT_ID,
        ];

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertSame($expectedProductIds, $resultProductListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds());
    }

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleBlacklistShouldAddOneMessage(): void
    {
        //Assign
        $productBundleCollection = $this->createProductBundleCollectionTransfer(static::BUNDLE_PRODUCT_ID);
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createBlacklistProductListTransfer([static::PRODUCT_ID_1]);

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertCount(1, $resultProductListResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleBlacklistShouldNotAddBundle(): void
    {
        //Assign
        $productBundleCollection = $this->createEmptyProductBundleCollectionTransfer();
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createBlacklistProductListTransfer([static::PRODUCT_ID_1]);
        $expectedProductIds = [
            static::PRODUCT_ID_1,
        ];

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertSame($expectedProductIds, $resultProductListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds());
    }

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleBlacklistShouldNotAddMessages(): void
    {
        //Assign
        $productBundleCollection = $this->createEmptyProductBundleCollectionTransfer();
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createBlacklistProductListTransfer([static::PRODUCT_ID_1]);

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertEmpty($resultProductListResponseTransfer->getMessages());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductBundleCollectionTransfer|null $productBundleCollection
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(
        ?ProductBundleCollectionTransfer $productBundleCollection = null
    ): MockObject {
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this
            ->getMockBuilder(ProductBundleProductListConnectorToProductBundleFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock
            ->method('getProductBundleCollectionByCriteriaFilter')
            ->willReturn($productBundleCollection);

        return $productBundleProductListConnectorToProductBundleFacadeBridgeMock;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function createBlacklistProductListTransfer(array $productIds = []): ProductListTransfer
    {
        return $this->tester->createProductListTransfer(
            $productIds,
            static::PRODUCT_LIST_TYPE_BLACKLIST
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    protected function createProductBundleCollectionTransfer(int $idProductConcrete): ProductBundleCollectionTransfer
    {
        $productBundle = (new ProductBundleTransfer())
            ->setIdProductConcreteBundle($idProductConcrete);
        $productBundles = new ArrayObject([$productBundle]);
        $productBundleCollection = (new ProductBundleCollectionTransfer())->setProductBundles($productBundles);

        return $productBundleCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductBundleCollectionTransfer
     */
    protected function createEmptyProductBundleCollectionTransfer(): ProductBundleCollectionTransfer
    {
        return (new ProductBundleCollectionTransfer())->setProductBundles(new ArrayObject());
    }
}
