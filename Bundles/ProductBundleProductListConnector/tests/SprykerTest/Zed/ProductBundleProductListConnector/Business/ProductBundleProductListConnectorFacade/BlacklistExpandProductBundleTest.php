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
use Generated\Shared\Transfer\ProductListResponseTransfer;
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
     * @var \SprykerTest\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductBundleBlacklistAddBundle(): void
    {
        $productBundleCollection = $this->createProductBundleCollectionTransfer(static::BUNDLE_PRODUCT_ID);
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacade($productBundleProductListConnectorToProductBundleFacadeBridgeMock);

        $productListResponseTransfer = $this->createBlacklistProductListResponseTransfer([static::PRODUCT_ID_1]);

        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductBundle($productListResponseTransfer);

        $expectedProductIds = [
            static::PRODUCT_ID_1,
            static::BUNDLE_PRODUCT_ID,
        ];

        $this->assertSame($expectedProductIds, $resultProductListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds());
    }

    /**
     * @return void
     */
    public function testExpandProductBundleBlacklistShouldNotAddBundle(): void
    {
        $productBundleCollection = $this->createEmptyProductBundleCollectionTransfer();
        $productBundleProductListConnectorToProductBundleFacadeBridgeMock = $this->getProductBundleProductListConnectorToProductBundleFacadeBridgeMock($productBundleCollection);
        $productBundleProductListConnectorFacade = $this->tester->getFacade($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productListResponseTransfer = $this->createBlacklistProductListResponseTransfer([static::PRODUCT_ID_1]);

        $resultProductListResponseTransfer = $productBundleProductListConnectorFacade->expandProductBundle($productListResponseTransfer);

        $expectedProductIds = [
            static::PRODUCT_ID_1,
        ];

        $this->assertSame($expectedProductIds, $resultProductListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds());
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
            ->method('findProductBundleCollectionByAssignedIdProductConcrete')
            ->willReturn($productBundleCollection);

        return $productBundleProductListConnectorToProductBundleFacadeBridgeMock;
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function createBlacklistProductListResponseTransfer(array $productIds = []): ProductListResponseTransfer
    {
        return $this->tester->createProductListResponseTransfer(
            $productIds,
            $this->tester->createProductBundleProductListConnectorConfig()->getProductListTypeBlacklist()
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
            ->setIdProductConcrete($idProductConcrete);
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
