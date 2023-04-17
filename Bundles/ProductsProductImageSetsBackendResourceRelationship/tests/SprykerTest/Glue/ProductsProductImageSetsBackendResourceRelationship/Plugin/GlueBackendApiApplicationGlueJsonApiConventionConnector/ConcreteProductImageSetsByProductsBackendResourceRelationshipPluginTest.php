<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductsProductImageSetsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\ApiProductImageSetsAttributesTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector\ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin;
use SprykerTest\Glue\ProductsProductImageSetsBackendResourceRelationship\ProductsProductImageSetsBackendResourceRelationshipTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductsProductImageSetsBackendResourceRelationship
 * @group Plugin
 * @group GlueBackendApiApplicationGlueJsonApiConventionConnector
 * @group ConcreteProductImageSetsByProductsBackendResourceRelationshipPluginTest
 * Add your own group annotations below this line
 */
class ConcreteProductImageSetsByProductsBackendResourceRelationshipPluginTest extends Unit
{
    /**
     * @uses \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS = 'concrete-product-image-sets';

    /**
     * @var \SprykerTest\Glue\ProductsProductImageSetsBackendResourceRelationship\ProductsProductImageSetsBackendResourceRelationshipTester
     */
    protected ProductsProductImageSetsBackendResourceRelationshipTester $tester;

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddProductImageSetsRelationshipToGlueResourceTransfer(): void
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

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(1, $glueResourceTransfers[0]->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldShouldSkipExpansionDueToWrongResourceType(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)->setType('fake-type')];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        $this->assertCount(1, $glueResourceTransfers);
        $this->assertCount(0, $glueResourceTransfers[0]->getRelationships());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectProductImageSetRelationshipId(): void
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

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $this->assertSame($productConcreteTransfer->getSku(), $glueResourceTransfer->getId());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectProductImageSetRelationshipType(): void
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

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $this->assertSame(static::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS, $glueResourceTransfer->getType());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddCorrectProductImageSetRelationshipAttributes(): void
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

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, new GlueRequestTransfer());

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $apiProductImageSetsAttributesTransfer = (new ApiProductImageSetsAttributesTransfer())
            ->fromArray($glueResourceTransfer->getAttributes()->toArray(), true);

        /** @var \Generated\Shared\Transfer\ApiProductsImageSetAttributesTransfer $apiProductsImageSetAttributesTransfer */
        $apiProductsImageSetAttributesTransfer = $apiProductImageSetsAttributesTransfer->getImageSets()->getIterator()->current();

        $this->assertSame('fake-name', $apiProductsImageSetAttributesTransfer->getName());
        $this->assertCount(2, $apiProductsImageSetAttributesTransfer->getImages());
    }

    /**
     * @return void
     */
    public function testAddRelationshipsShouldAddProductImageSetsRelationshipToGlueResourceFilteredByLocale(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            ProductImageSetTransfer::LOCALE => $localeTransfer,
        ]);
        $this->tester->haveProductImageSet([
            ProductImageSetTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
        ]);

        $glueResourceTransfers = [$this->tester->createProductConcreteResource($productConcreteTransfer)];

        // Act
        (new ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin())
            ->addRelationships($glueResourceTransfers, (new GlueRequestTransfer())->setLocale($localeTransfer->getLocaleName()));

        // Assert
        /** @var \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer */
        $glueRelationshipTransfer = $glueResourceTransfers[0]->getRelationships()->getIterator()->current();
        /** @var \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer */
        $glueResourceTransfer = $glueRelationshipTransfer->getResources()->getIterator()->current();

        $apiProductImageSetsAttributesTransfer = (new ApiProductImageSetsAttributesTransfer())
            ->fromArray($glueResourceTransfer->getAttributes()->toArray(), true);

        $this->assertCount(1, $apiProductImageSetsAttributesTransfer->getImageSets());
    }
}
