<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductOffer\PriceProductOfferDependencyProvider;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductOffer
 * @group Business
 * @group Facade
 * @group PriceProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class PriceProductOfferFacadeTest extends Unit
{
    use DataCleanupHelperTrait;

    /**
     * @var \SprykerTest\Zed\PriceProductOffer\PriceProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensurePriceProductOfferTableIsEmpty();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getDataCleanupHelper()->_addCleanup(function (): void {
            $this->tester->ensurePriceProductOfferTableIsEmpty();
        });
    }

    /**
     * @return void
     */
    public function testSaveProductOfferPricesCallsPriceProductFacadeWithCorrectData(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $productOfferTransfer = ($this->tester->haveProductOffer())
            ->addPrice($priceProductTransfer)
            ->setIdProductConcrete(1);

        $priceProductFacadeMock = $this->getPriceProductFacadeMock();
        $priceProductFacadeMock
            ->expects($this->once())
            ->method('persistProductConcretePriceCollection')
            ->will($this->returnCallback(function (ProductConcreteTransfer $productConcreteTransfer) use ($productOfferTransfer) {
                $priceProductTransfer = $productConcreteTransfer->getPrices()[0];
                $this->assertCount(1, $productConcreteTransfer->getPrices());
                $this->assertSame($productOfferTransfer->getIdProductConcrete(), $productConcreteTransfer->getIdProductConcrete());
                $this->assertSame($productOfferTransfer->getIdProductConcrete(), $priceProductTransfer->getIdProduct());
                $this->assertSame(PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER, $priceProductTransfer->getPriceDimension()->getType());

                return $productConcreteTransfer;
            }));

        $this->tester->setDependency(PriceProductOfferDependencyProvider::FACADE_PRICE_PRODUCT, $priceProductFacadeMock);

        // Act
        $this->tester->getFacade()->saveProductOfferPrices($productOfferTransfer);
    }

    /**
     * @return void
     */
    public function testSavePriceProductOfferRelationCreatesPriceProductOfferEntity(): void
    {
        // Arrange
        $productOfferTransfer = $this->tester->haveProductOffer();
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductDimensionTransfer = $priceProductTransfer->getPriceDimension();
        $priceProductDimensionTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $priceProductTransfer->setPriceDimension($priceProductDimensionTransfer);

        // Act
        $this->tester->getFacade()->savePriceProductOfferRelation($priceProductTransfer);
        $priceProductOfferTransfer = $this->tester->getPriceProductOfferByIdProductOffer($productOfferTransfer->getIdProductOffer());

        // Assert
        $this->assertSame($priceProductDimensionTransfer->getIdProductOffer(), $priceProductOfferTransfer->getFkProductOffer());
        $this->assertSame((string)$priceProductTransfer->getMoneyValue()->getIdEntity(), $priceProductOfferTransfer->getFkPriceProductStore());
    }

    /**
     * @return void
     */
    public function testSavePriceProductOfferRelationUpdatesPriceProductOfferEntity(): void
    {
        // Arrange
        $priceProductTransfer1 = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductOfferTransfer = $this->tester->havePriceProductOffer([
            PriceProductOfferTransfer::FK_PRICE_PRODUCT_STORE => $priceProductTransfer1->getMoneyValue()->getIdEntity(),
        ]);
        $priceProductTransfer2 = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductDimensionTransfer = $priceProductTransfer2->getPriceDimension();
        $priceProductDimensionTransfer->setIdProductOffer($priceProductOfferTransfer->getFkProductOffer());
        $priceProductDimensionTransfer->setIdPriceProductOffer($priceProductOfferTransfer->getIdPriceProductOffer());
        $priceProductTransfer2->setPriceDimension($priceProductDimensionTransfer);

        // Act
        $this->tester->getFacade()->savePriceProductOfferRelation($priceProductTransfer2);
        $priceProductOfferTransfer = $this->tester->getPriceProductOfferByIdProductOffer($priceProductOfferTransfer->getFkProductOffer());

        // Assert
        $this->assertSame($priceProductOfferTransfer->getFkPriceProductStore(), (string)$priceProductTransfer2->getMoneyValue()->getIdEntity());
    }

    /**
     * @return void
     */
    public function testExpandProductOfferWithPricesExpandsProductOfferWithCorrectPriceProduct(): void
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductOfferTransfer = $this->tester->havePriceProductOffer([
            PriceProductOfferTransfer::FK_PRICE_PRODUCT_STORE => $priceProductTransfer->getMoneyValue()->getIdEntity(),
        ]);

        // Act
        $priceProductTransfers = $this->tester
            ->getFacade()
            ->expandProductOfferWithPrices((new ProductOfferTransfer())->setIdProductOffer($priceProductOfferTransfer->getFkProductOffer()))
            ->getPrices();

        // Assert
        $this->assertCount(1, $priceProductTransfers);
        $this->assertSame(
            $priceProductTransfer->getIdPriceProduct(),
            $priceProductTransfers[0]->getIdPriceProduct()
        );
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceProductOffer\Dependency\Facade\PriceProductOfferToPriceProductFacadeInterface
     */
    protected function getPriceProductFacadeMock(): PriceProductOfferToPriceProductFacadeInterface
    {
        $priceProductFacadeMock = $this->getMockBuilder(PriceProductOfferToPriceProductFacadeBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $priceProductFacadeMock;
    }
}
