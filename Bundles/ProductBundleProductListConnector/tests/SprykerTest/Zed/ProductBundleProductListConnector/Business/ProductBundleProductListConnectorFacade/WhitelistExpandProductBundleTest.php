<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeBridge;

/**
 * Auto-generated group annotations
 *
 * @group Spryker
 * @group Zed
 * @group ProductBundleProductListConnector
 * @group Business
 * @group ProductBundleProductListConnectorFacade
 * @group WhitelistExpandProductBundleTest
 * Add your own group annotations below this line
 */
class WhitelistExpandProductBundleTest extends Unit
{
    protected const PRODUCT_ID_1 = 1;

    protected const BUNDLE_PRODUCT_ID = 20;

    /**
     * @uses \Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap::COL_TYPE_WHITELIST
     */
    protected const PRODUCT_LIST_TYPE_WHITELIST = 'whitelist';

    /**
     * @var \SprykerTest\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleWhitelistShouldAddBundle(): void
    {
        //Assign
        $bundledProducts = (new ProductForBundleTransfer())->setIdProductConcrete(static::BUNDLE_PRODUCT_ID);
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(new ArrayObject([$bundledProducts]));
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createWhitelistProductListTransfer([static::PRODUCT_ID_1]);
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
    public function testExpandProductListWithProductBundleWhitelistShouldAddOneMessage(): void
    {
        //Assign
        $bundledProducts = (new ProductForBundleTransfer())->setIdProductConcrete(static::BUNDLE_PRODUCT_ID);
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(new ArrayObject([$bundledProducts]));
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createWhitelistProductListTransfer([static::PRODUCT_ID_1]);

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertCount(1, $resultProductListResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testExpandProductListWithProductBundleWhitelistShouldNotAddBundle(): void
    {
        //Assign
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(new ArrayObject());
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createWhitelistProductListTransfer([static::PRODUCT_ID_1]);
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
    public function testExpandProductListWithProductBundleWhitelistShouldNotAddMessages(): void
    {
        //Assign
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(new ArrayObject());
        $productBundleProductListConnectorFacade = $this->tester->getFacadeWitMockDependency($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListTransfer = $this->createWhitelistProductListTransfer([static::PRODUCT_ID_1]);

        //Act
        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductListWithProductBundle($productListTransfer);

        //Assert
        $this->assertEmpty($resultProductListResponseTransfer->getMessages());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $bundledProducts
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductBundleProductListConnectorToProductBundleFacadeBridgeMock(
        ArrayObject $bundledProducts
    ): MockObject {
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this
            ->getMockBuilder(ProductBundleProductListConnectorToProductBundleFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock
            ->method('findBundledProductsByIdProductConcrete')
            ->willReturn($bundledProducts);

        return $productBundleProductListConnectorToProductBundleFacadeBridgeMock;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    protected function createWhitelistProductListTransfer(array $productIds = []): ProductListTransfer
    {
        return $this->tester->createProductListTransfer(
            $productIds,
            static::PRODUCT_LIST_TYPE_WHITELIST
        );
    }
}
