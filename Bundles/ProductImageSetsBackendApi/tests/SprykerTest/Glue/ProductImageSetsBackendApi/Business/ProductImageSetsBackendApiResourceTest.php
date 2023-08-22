<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductImageSetsBackendApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\ProductImageSetConditionsTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use SprykerTest\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductImageSetsBackendApi
 * @group Business
 * @group ProductImageSetsBackendApiResourceTest
 * Add your own group annotations below this line
 */
class ProductImageSetsBackendApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS = 'concrete-product-image-sets';

    /**
     * @var \SprykerTest\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiTester
     */
    protected ProductImageSetsBackendApiTester $tester;

    /**
     * @return void
     */
    public function testGetProductImageSetResourceCollectionShouldReturnListOfProductImageSetResources(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions((new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()));

        // Act
        $productImageSetResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetResourceCollectionTransfer->getProductImageSetResources());
    }

    /**
     * @return void
     */
    public function testGetProductImageSetResourceCollectionShouldReturnCorrectResourceId(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions((new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()));

        // Act
        $productImageSetResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $concreteProductImageSetResource */
        $concreteProductImageSetResource = $productImageSetResourceCollectionTransfer->getProductImageSetResources()->getIterator()->current();
        $this->assertSame($productConcreteTransfer->getSku(), $concreteProductImageSetResource->getId());
    }

    /**
     * @return void
     */
    public function testGetProductImageSetResourceCollectionShouldReturnCorrectResourceType(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions((new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()));

        // Act
        $productImageSetResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $concreteProductImageSetResource */
        $concreteProductImageSetResource = $productImageSetResourceCollectionTransfer->getProductImageSetResources()->getIterator()->current();
        $this->assertSame(static::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS, $concreteProductImageSetResource->getType());
    }

    /**
     * @return void
     */
    public function testGetProductImageSetResourceCollectionShouldReturnCorrectResourceAttributes(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::NAME => 'fake-name',
            ProductImageSetTransfer::PRODUCT_IMAGES => [
                (new ProductImageBuilder())->build(),
                (new ProductImageBuilder())->build(),
            ],
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions((new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()));

        // Act
        $productImageSetResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $concreteProductImageSetResource */
        $concreteProductImageSetResource = $productImageSetResourceCollectionTransfer->getProductImageSetResources()->getIterator()->current();
        $productImageSetsBackendApiAttributesTransfer = (new ProductImageSetsBackendApiAttributesTransfer())
            ->fromArray($concreteProductImageSetResource->getAttributes()->toArray(), true);

        /** @var \Generated\Shared\Transfer\ProductImageSetBackendApiAttributesTransfer $productImageSetBackendApiAttributesTransfer */
        $productImageSetBackendApiAttributesTransfer = $productImageSetsBackendApiAttributesTransfer->getImageSets()->getIterator()->current();

        $this->assertSame('fake-name', $productImageSetBackendApiAttributesTransfer->getName());
        $this->assertCount(2, $productImageSetBackendApiAttributesTransfer->getImages());
        $this->assertNotEmpty($productImageSetBackendApiAttributesTransfer->getImages()->offsetGet(0)->getExternalUrlLarge());
        $this->assertNotEmpty($productImageSetBackendApiAttributesTransfer->getImages()->offsetGet(0)->getExternalUrlSmall());
        $this->assertNotEmpty($productImageSetBackendApiAttributesTransfer->getImages()->offsetGet(1)->getExternalUrlLarge());
        $this->assertNotEmpty($productImageSetBackendApiAttributesTransfer->getImages()->offsetGet(1)->getExternalUrlSmall());
    }

    /**
     * @return void
     */
    public function testGetProductImageSetResourceCollectionShouldReturnGroupedResources(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $productImageSetCriteriaTransfer = (new ProductImageSetCriteriaTransfer())
            ->setProductImageSetConditions((new ProductImageSetConditionsTransfer())->addSku($productConcreteTransfer->getSku()));

        // Act
        $productImageSetResourceCollectionTransfer = $this->tester
            ->getResource()
            ->getConcreteProductImageSetResourceCollection($productImageSetCriteriaTransfer);

        // Assert
        $this->assertCount(1, $productImageSetResourceCollectionTransfer->getProductImageSetResources());

        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $concreteProductImageSetResource */
        $concreteProductImageSetResource = $productImageSetResourceCollectionTransfer->getProductImageSetResources()->getIterator()->current();
        $productImageSetsBackendApiAttributesTransfer = (new ProductImageSetsBackendApiAttributesTransfer())
            ->fromArray($concreteProductImageSetResource->getAttributes()->toArray(), true);

        $this->assertCount(2, $productImageSetsBackendApiAttributesTransfer->getImageSets());
    }
}
