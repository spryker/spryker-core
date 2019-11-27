<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyAvailabilityEntityTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapper;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductAvailabilitiesRestApi
 * @group Processor
 * @group Mapper
 * @group ProductAvailabilitiesResourceMapperTest
 * Add your own group annotations below this line
 */
class ProductAvailabilitiesResourceMapperTest extends Unit
{
    protected const PRODUCTS_AVAILABILITY_QUANTITY = 10;
    protected const PRODUCTS_AVAILABILITY_IS_NEVER_OUT_OF_STOCK = false;
    protected const PRODUCT_CONCRETE_SKU = '001_25904006';
    protected const PRODUCT_CONCRETE_AVAILABILITY_ID = '1';
    protected const PRODUCT_ABSTRACT_SKU = '001';

    /**
     * @var \SprykerTest\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesMapperTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductsAvailabilityMapperReturnCorrectRestResourceForAvailableConcreteProducts(): void
    {
        $mapper = $this->getConcreteProductsAvailabilityResourceMapper();
        $transfer = $this->getProductConcreteAvailabilityTransferWithAvailableProducts();

        /** @var \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer $attributesTransfer */
        $attributesTransfer = $mapper->mapAvailabilityTransferToRestConcreteProductAvailabilityAttributesTransfer($transfer);

        $this->assertInstanceOf(RestConcreteProductAvailabilityAttributesTransfer::class, $attributesTransfer);
        $this->assertTrue($attributesTransfer->getAvailability());
        $this->assertEquals($attributesTransfer->getIsNeverOutOfStock(), static::PRODUCTS_AVAILABILITY_IS_NEVER_OUT_OF_STOCK);
        $this->assertEquals($attributesTransfer->getQuantity(), new Decimal(static::PRODUCTS_AVAILABILITY_QUANTITY));
    }

    /**
     * @return void
     */
    public function testProductsAvailabilityMapperReturnCorrectRestResourceForUnavailableConcreteProducts(): void
    {
        $mapper = $this->getConcreteProductsAvailabilityResourceMapper();
        $transfer = $this->getProductConcreteAvailabilityTransferWithUnavailableProducts();

        /** @var \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer $attributesTransfer */
        $attributesTransfer = $mapper->mapAvailabilityTransferToRestConcreteProductAvailabilityAttributesTransfer($transfer);

        $this->assertInstanceOf(RestConcreteProductAvailabilityAttributesTransfer::class, $attributesTransfer);
        $this->assertFalse($attributesTransfer->getAvailability());
        $this->assertEquals($attributesTransfer->getIsNeverOutOfStock(), static::PRODUCTS_AVAILABILITY_IS_NEVER_OUT_OF_STOCK);
        $this->assertTrue($attributesTransfer->getQuantity()->isZero());
    }

    /**
     * @return void
     */
    public function testProductsAvailabilityMapperReturnCorrectRestResourceForAvailableProductsAbstract(): void
    {
        $mapper = $this->getAbstractProductsAvailabilityResourceMapper();
        $transfer = $this->getProductAbstractAvailabilityTransferWithAvailableProducts();

        /** @var \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer $attributesTransfer */
        $attributesTransfer = $mapper->mapAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer($transfer);

        $this->assertInstanceOf(RestAbstractProductAvailabilityAttributesTransfer::class, $attributesTransfer);
        $this->assertTrue($attributesTransfer->getAvailability());
        $this->assertEquals($attributesTransfer->getQuantity(), new Decimal(static::PRODUCTS_AVAILABILITY_QUANTITY));
    }

    /**
     * @return void
     */
    public function testProductsAvailabilityMapperReturnCorrectRestResourceForUnavailableProductsAbstract(): void
    {
        $mapper = $this->getAbstractProductsAvailabilityResourceMapper();
        $transfer = $this->getProductAbstractAvailabilityTransferWithUnavailableProducts();

        /** @var \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer $attributesTransfer */
        $attributesTransfer = $mapper->mapAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer($transfer);

        $this->assertInstanceOf(RestAbstractProductAvailabilityAttributesTransfer::class, $attributesTransfer);
        $this->assertFalse($attributesTransfer->getAvailability());
        $this->assertNotNull($attributesTransfer->getQuantity());
        $this->assertTrue($attributesTransfer->getQuantity()->isZero());
    }

    /**
     * @return \Generated\Shared\Transfer\SpyAvailabilityEntityTransfer
     */
    protected function getProductConcreteAvailabilityTransferWithAvailableProducts(): SpyAvailabilityEntityTransfer
    {
        $spyAvailabilityEntityTransfer = new SpyAvailabilityEntityTransfer();
        $spyAvailabilityEntityTransfer->setQuantity(static::PRODUCTS_AVAILABILITY_QUANTITY);
        $spyAvailabilityEntityTransfer->setIsNeverOutOfStock(static::PRODUCTS_AVAILABILITY_IS_NEVER_OUT_OF_STOCK);
        $spyAvailabilityEntityTransfer->setSku(static::PRODUCT_CONCRETE_SKU);
        $spyAvailabilityEntityTransfer->setIdAvailability(static::PRODUCT_CONCRETE_AVAILABILITY_ID);

        return $spyAvailabilityEntityTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SpyAvailabilityEntityTransfer
     */
    protected function getProductConcreteAvailabilityTransferWithUnavailableProducts(): SpyAvailabilityEntityTransfer
    {
        $spyAvailabilityEntityTransfer = new SpyAvailabilityEntityTransfer();
        $spyAvailabilityEntityTransfer->setQuantity(0);
        $spyAvailabilityEntityTransfer->setIsNeverOutOfStock(static::PRODUCTS_AVAILABILITY_IS_NEVER_OUT_OF_STOCK);
        $spyAvailabilityEntityTransfer->setSku(static::PRODUCT_CONCRETE_SKU);
        $spyAvailabilityEntityTransfer->setIdAvailability(static::PRODUCT_CONCRETE_AVAILABILITY_ID);

        return $spyAvailabilityEntityTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    protected function getProductAbstractAvailabilityTransferWithAvailableProducts(): SpyAvailabilityAbstractEntityTransfer
    {
        $spyAvailabilityAbstractEntityTransfer = new SpyAvailabilityAbstractEntityTransfer();
        $spyAvailabilityAbstractEntityTransfer->setAbstractSku(static::PRODUCT_ABSTRACT_SKU);
        $spyAvailabilityAbstractEntityTransfer->setQuantity(static::PRODUCTS_AVAILABILITY_QUANTITY);
        $spyAvailabilityAbstractEntityTransfer->addSpyAvailabilities($this->getProductConcreteAvailabilityTransferWithAvailableProducts());

        return $spyAvailabilityAbstractEntityTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    protected function getProductAbstractAvailabilityTransferWithUnavailableProducts(): SpyAvailabilityAbstractEntityTransfer
    {
        $spyAvailabilityAbstractEntityTransfer = new SpyAvailabilityAbstractEntityTransfer();
        $spyAvailabilityAbstractEntityTransfer->setAbstractSku(static::PRODUCT_ABSTRACT_SKU);
        $spyAvailabilityAbstractEntityTransfer->setQuantity(0);
        $spyAvailabilityAbstractEntityTransfer->addSpyAvailabilities($this->getProductConcreteAvailabilityTransferWithUnavailableProducts());

        return $spyAvailabilityAbstractEntityTransfer;
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    protected function getAbstractProductsAvailabilityResourceMapper(): AbstractProductAvailabilitiesResourceMapperInterface
    {
        return new AbstractProductAvailabilitiesResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapper
     */
    protected function getConcreteProductsAvailabilityResourceMapper(): ConcreteProductAvailabilitiesResourceMapper
    {
        return new ConcreteProductAvailabilitiesResourceMapper();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected function getResourceBuilder()
    {
        return $this->getMockBuilder(RestResourceBuilder::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }
}
