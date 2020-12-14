<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductOffer\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
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
     * @return void
     */
    public function testValidateProductOfferPricesIsSuccessful()
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductTransfer->getMoneyValue()->setNetAmount(10);
        $priceProductTransfer->getMoneyValue()->setGrossAmount(100);

        // Act
        $collectionValidationResponseTransfer = $this->tester
            ->getFacade()
            ->validateProductOfferPrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertTrue($collectionValidationResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateProductOfferPricesFailValidUniqueStoreCurrencyGrossNetConstraint()
    {
        // Arrange
        $priceProductTransferSrc = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductTransferDst = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);

        $priceProductTransferDst->getMoneyValue()->setStore($priceProductTransferSrc->getMoneyValue()->getStore());
        $priceProductTransferDst->getMoneyValue()->setFkStore($priceProductTransferSrc->getMoneyValue()->getFkStore());
        $priceProductTransferDst->getMoneyValue()->setCurrency($priceProductTransferSrc->getMoneyValue()->getCurrency());
        $priceProductTransferDst->getPriceDimension()->setIdProductOffer($priceProductTransferSrc->getPriceDimension()->getIdProductOffer());
        $priceProductTransferDst->setPriceType($priceProductTransferSrc->getPriceType());

        // Act
        $collectionValidationResponseTransfer = $this->tester
            ->getFacade()
            ->validateProductOfferPrices(new ArrayObject([$priceProductTransferDst]));

        // Assert
        $this->assertFalse($collectionValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $collectionValidationResponseTransfer->getValidationErrors());
        $this->assertSame(
            'The set of inputs Store and Currency needs to be unique.',
            $collectionValidationResponseTransfer->getValidationErrors()
                ->offsetGet(0)
                ->getMessage()
        );
    }

    /**
     * @dataProvider validateProductOfferPricesFailValidNetAmountValueDataProvider
     *
     * @param mixed $invalidValue
     *
     * @return void
     */
    public function testValidateProductOfferPricesFailValidNetAmountValue($invalidValue)
    {
        // Arrange
        $priceProductTransfer = $this->tester->havePriceProduct([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductTransfer->getMoneyValue()->setNetAmount($invalidValue);

        // Act
        $collectionValidationResponseTransfer = $this->tester
            ->getFacade()
            ->validateProductOfferPrices(new ArrayObject([$priceProductTransfer]));

        // Assert
        $this->assertFalse($collectionValidationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            'This value is not valid.',
            $collectionValidationResponseTransfer->getValidationErrors()
                ->offsetGet(0)
                ->getMessage()
        );
    }

    /**
     * @return array
     */
    public function validateProductOfferPricesFailValidNetAmountValueDataProvider(): array
    {
        return [
            [-1],
        ];
    }

    /**
     * @return void
     */
    public function testCountPriceProductOfferEntities()
    {
        // Arrange
        $priceProductOffer1 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductOffer2 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds([
            $priceProductOffer1->getPriceDimension()->getIdPriceProductOffer(),
            $priceProductOffer2->getPriceDimension()->getIdPriceProductOffer(),
        ]);

        // Act
        $count = $this->tester
            ->getFacade()
            ->count($priceProductOfferCriteriaTransfer);

        // Assert
        $this->assertSame(2, $count);
    }

    /**
     * @return void
     */
    public function testDeletePriceProductOfferEntities()
    {
        // Arrange
        $priceProduct1 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProduct2 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $idPriceProductOffer1 = $priceProduct1->getPriceDimension()->getIdPriceProductOffer();
        $idPriceProductOffer2 = $priceProduct2->getPriceDimension()->getIdPriceProductOffer();

        $priceProductOfferCollectionTransfer = new PriceProductOfferCollectionTransfer();
        $priceProductOfferCollectionTransfer->addPriceProductOffer(
            (new PriceProductOfferTransfer())->setIdPriceProductOffer($idPriceProductOffer1)
        )->addPriceProductOffer(
            (new PriceProductOfferTransfer())->setIdPriceProductOffer($idPriceProductOffer2)
        );

        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds([
            $idPriceProductOffer1,
            $idPriceProductOffer2,
        ]);

        // Act
        $this->tester
            ->getFacade()
            ->deleteProductOfferPrices($priceProductOfferCollectionTransfer);
        $count = $this->tester
            ->getFacade()
            ->count($priceProductOfferCriteriaTransfer);

        // Assert
        $this->assertSame(0, $count);
    }

    /**
     * @return void
     */
    public function testGetProductOfferPricesSuccess()
    {
        // Arrange
        $priceProduct1 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProduct2 = $this->tester->havePriceProductSaved([PriceProductTransfer::SKU_PRODUCT_ABSTRACT => 'sku']);
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds([
            $priceProduct1->getPriceDimension()->getIdPriceProductOffer(),
            $priceProduct2->getPriceDimension()->getIdPriceProductOffer(),
        ]);

        // Act
        $productOfferPrices = $this->tester
            ->getFacade()
            ->getProductOfferPrices($priceProductOfferCriteriaTransfer);

        // Assert
        $this->assertCount(2, $productOfferPrices);
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
