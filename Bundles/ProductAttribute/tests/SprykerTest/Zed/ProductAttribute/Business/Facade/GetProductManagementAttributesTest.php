<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAttribute
 * @group Business
 * @group GetProductManagementAttributesTest
 * Add your own group annotations below this line
 */
class GetProductManagementAttributesTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ProductAttribute\ProductAttributeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetProductManagementAttributesRetrievesAttributes(): void
    {
        //Arrange
        $productManagementAttributeEntity = $this->tester->createProductManagementAttributeEntity();
        $key = $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey();

        $productManagementAttributeFilterTransfer = (new ProductManagementAttributeFilterTransfer())
            ->addKey($key);

        //Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        //Assert
        $this->assertCount(1, $productManagementAttributeCollectionTransfer->getProductManagementAttributes());
        $this->assertSame(
            $key,
            $productManagementAttributeCollectionTransfer->getProductManagementAttributes()->offsetGet(0)->getKey()
        );
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributesEnsureValuesInProductManagementAttribute(): void
    {
        //Arrange
        $productManagementAttributeEntity = $this->tester->createProductManagementAttributeEntity(['a', 'b', 'c']);
        $this->tester->addAttributeValueTranslations($productManagementAttributeEntity);

        $productManagementAttributeFilterTransfer = (new ProductManagementAttributeFilterTransfer())
            ->addKey($productManagementAttributeEntity->getSpyProductAttributeKey()->getKey());

        //Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        //Assert
        $this->assertCount(3, $productManagementAttributeCollectionTransfer->getProductManagementAttributes()->offsetGet(0)->getValues());
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributesEnsureThatLocalNameExistsInLocalizedValues(): void
    {
        //Arrange
        $productManagementAttributeEntity = $this->tester->createProductManagementAttributeEntity(['a', 'b', 'c']);
        $this->tester->addAttributeValueTranslations($productManagementAttributeEntity);

        $productManagementAttributeFilterTransfer = (new ProductManagementAttributeFilterTransfer())
            ->addKey($productManagementAttributeEntity->getSpyProductAttributeKey()->getKey());

        //Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        //Assert
        /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer */
        $productManagementAttributeTransfer = $productManagementAttributeCollectionTransfer->getProductManagementAttributes()->offsetGet(0);

        $this->assertCount(2, $productManagementAttributeTransfer->getValues()->offsetGet(0)->getLocalizedValues());
        $this->assertNotNull(
            $productManagementAttributeTransfer->getValues()->offsetGet(0)->getLocalizedValues()->offsetGet(0)->getLocaleName()
        );
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributesRetrievesAttributesWithFilterLimit(): void
    {
        //Arrange
        $this->tester->createProductManagementAttributeEntity();
        $this->tester->createProductManagementAttributeEntity();

        $productManagementAttributeFilterTransfer = (new ProductManagementAttributeFilterTransfer())
            ->setFilter((new FilterTransfer())->setLimit(1));

        //Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        //Assert
        $this->assertCount(1, $productManagementAttributeCollectionTransfer->getProductManagementAttributes());
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributesRetrievesAttributesWithPagination(): void
    {
        //Arrange
        $this->tester->createProductManagementAttributeEntity();
        $this->tester->createProductManagementAttributeEntity();

        //Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()
            ->getProductManagementAttributes(new ProductManagementAttributeFilterTransfer());

        //Assert
        $this->assertIsNumeric($productManagementAttributeCollectionTransfer->getPagination()->getNbResults());
    }
}
