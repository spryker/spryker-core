<?php

namespace SprykerTest\Glue\ProductImageSetsRestApi\Processor\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageSetStorageTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapper;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group ProductImageSetsRestApi
 * @group Processor
 * @group Mapper
 * @group ProductImageSetsMapperTest
 * Add your own group annotations below this line
 */
class ProductImageSetsMapperTest extends Unit
{
    protected const IMAGE_SET_NAME = 'default';
    protected const ID_PRODUCT_IMAGE = 1;
    protected const IMAGE_EXTERNAL_URL_LARGE = '//images.icecat.biz/img/norm/high/25904006-8438.jpg';
    protected const IMAGE_EXTERNAL_URL_SMALL = '//images.icecat.biz/img/norm/medium/25904006-8438.jpg';

    /**
     * @return void
     */
    public function testMapProductAbstractImagesTransferToRestResource(): void
    {
        $imageSetsTransfer = $this->getProductAbstractImageStorageTransfer();
        $mapper = $this->getAbstractProductMapper();

        $restResource = $mapper->mapAbstractProductImageSetsTransferToRestResource($imageSetsTransfer);
        /** @var \Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer $attributesTransfer */
        $attributesTransfer = $restResource->getAttributes();

        $this->assertInstanceOf(RestProductImageSetsAttributesTransfer::class, $attributesTransfer);
        $this->assertEquals($attributesTransfer->getName(), static::IMAGE_SET_NAME);
        $this->assertEquals($attributesTransfer->getImages()[0]->getExternalUrlLarge(), static::IMAGE_EXTERNAL_URL_LARGE);
        $this->assertEquals($attributesTransfer->getImages()[0]->getExternalUrlSmall(), static::IMAGE_EXTERNAL_URL_SMALL);
    }

    /**
     * @return void
     */
    public function testMapProductConcreteImagesTransferToRestResource(): void
    {
        $imageSetsTransfer = $this->getProductConcreteImageStorageTransfer();
        $mapper = $this->getConcreteProductMapper();

        $restResource = $mapper->mapConcreteProductImageSetsTransferToRestResource($imageSetsTransfer, '100_100');
        /** @var \Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer $attributesTransfer */
        $attributesTransfer = $restResource->getAttributes();

        $this->assertInstanceOf(RestProductImageSetsAttributesTransfer::class, $attributesTransfer);
        $this->assertEquals($attributesTransfer->getName(), static::IMAGE_SET_NAME);
        $this->assertEquals($attributesTransfer->getImages()[0]->getExternalUrlLarge(), static::IMAGE_EXTERNAL_URL_LARGE);
        $this->assertEquals($attributesTransfer->getImages()[0]->getExternalUrlSmall(), static::IMAGE_EXTERNAL_URL_SMALL);
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\AbstractProductImageSetsMapperInterface
     */
    protected function getAbstractProductMapper(): AbstractProductImageSetsMapperInterface
    {
        return new AbstractProductImageSetsMapper($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper\ConcreteProductImageSetsMapperInterface
     */
    protected function getConcreteProductMapper(): ConcreteProductImageSetsMapperInterface
    {
        return new ConcreteProductImageSetsMapper($this->getResourceBuilder());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected function getResourceBuilder(): RestResourceBuilderInterface
    {
        return $this->getMockBuilder(RestResourceBuilder::class)
            ->enableProxyingToOriginalMethods()
            ->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer
     */
    protected function getProductConcreteImageStorageTransfer(): ProductConcreteImageStorageTransfer
    {
        $productConcreteImageStorageTransfer = new ProductConcreteImageStorageTransfer();
        $productConcreteImageStorageTransfer->setImageSets($this->getProductImageSetsTransfers());

        return $productConcreteImageStorageTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer
     */
    protected function getProductAbstractImageStorageTransfer(): ProductAbstractImageStorageTransfer
    {
        $productConcreteImageStorageTransfer = new ProductAbstractImageStorageTransfer();
        $productConcreteImageStorageTransfer->setImageSets($this->getProductImageSetsTransfers());

        return $productConcreteImageStorageTransfer;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductImageSetStorageTransfer[]
     */
    protected function getProductImageSetsTransfers(): ArrayObject
    {
        $images = new ArrayObject();
        $images->append($this->getProductImageStorageTransfer());

        $imageSet = new ProductImageSetStorageTransfer();
        $imageSet->setName(static::IMAGE_SET_NAME);
        $imageSet->setImages($images);

        $imageSets = new ArrayObject();
        $imageSets->append($imageSet);

        return $imageSets;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageStorageTransfer
     */
    protected function getProductImageStorageTransfer(): ProductImageStorageTransfer
    {
        $productImageStorageTransfer = new ProductImageStorageTransfer();
        $productImageStorageTransfer->setIdProductImage(static::ID_PRODUCT_IMAGE);
        $productImageStorageTransfer->setExternalUrlLarge(static::IMAGE_EXTERNAL_URL_LARGE);
        $productImageStorageTransfer->setExternalUrlSmall(static::IMAGE_EXTERNAL_URL_SMALL);

        return $productImageStorageTransfer;
    }
}
