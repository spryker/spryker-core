<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAttribute\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeConditionsTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer;
use Orm\Zed\ProductAttribute\Persistence\SpyProductManagementAttributeQuery;
use SprykerTest\Zed\ProductAttribute\ProductAttributeBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAttribute
 * @group Business
 * @group GetProductManagementAttributeCollectionTest
 * Add your own group annotations below this line
 */
class GetProductManagementAttributeCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const PRODUCT_MANAGEMENT_ATTRIBUTE_KEY_NOT_EXISTS = 'not-valid';

    /**
     * @var string
     */
    protected const ATTRIBUTE_VALUE_ONE = 'one';

    /**
     * @var string
     */
    protected const ATTRIBUTE_VALUE_TWO = 'two';

    /**
     * @var \SprykerTest\Zed\ProductAttribute\ProductAttributeBusinessTester
     */
    protected ProductAttributeBusinessTester $tester;

    /**
     * @return void
     */
    public function _before(): void
    {
        $this->tester->ensureDatabaseTableIsEmpty(SpyProductManagementAttributeQuery::create());
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeCollectionShouldReturnEmptyCollectionWhenNoCriteriaMatched(): void
    {
        // Arrange
        $this->tester->haveProductManagementAttributeEntity();

        $productManagementAttributeCriteriaTransfer = (new ProductManagementAttributeCriteriaTransfer())
            ->setProductManagementAttributeConditions(
                (new ProductManagementAttributeConditionsTransfer())
                    ->addKey(static::PRODUCT_MANAGEMENT_ATTRIBUTE_KEY_NOT_EXISTS),
            );

        // Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()->getProductManagementAttributeCollection(
            $productManagementAttributeCriteriaTransfer,
        );

        // Assert
        $this->assertCount(0, $productManagementAttributeCollectionTransfer->getProductManagementAttributes());
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeCollectionReturnsCollectionWithFilterByKeyCriteria(): void
    {
        // Arrange
        $expectedValues = [static::ATTRIBUTE_VALUE_ONE, static::ATTRIBUTE_VALUE_TWO];
        $productManagementAttributeEntity = $this->tester->createProductManagementAttributeEntity($expectedValues);
        $this->tester->createProductManagementAttributeEntity(['c', 'd']);

        $productManagementAttributeCriteriaTransfer = (new ProductManagementAttributeCriteriaTransfer())
            ->setProductManagementAttributeConditions(
                (new ProductManagementAttributeConditionsTransfer())
                    ->addKey($productManagementAttributeEntity->getSpyProductAttributeKey()->getKey()),
            );

        // Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()->getProductManagementAttributeCollection(
            $productManagementAttributeCriteriaTransfer,
        );

        // Assert
        $this->assertCount(1, $productManagementAttributeCollectionTransfer->getProductManagementAttributes());
        /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer */
        $productManagementAttributeTransfer = $productManagementAttributeCollectionTransfer->getProductManagementAttributes()->getIterator()->current();
        $this->assertSame(
            $productManagementAttributeEntity->getIdProductManagementAttribute(),
            $productManagementAttributeTransfer->getIdProductManagementAttribute(),
        );
        $this->assertSame(
            $productManagementAttributeEntity->getSpyProductAttributeKey()->getKey(),
            $productManagementAttributeTransfer->getKey(),
        );
        $this->assertNotEmpty($productManagementAttributeTransfer->getLocalizedKeys());
        $this->assertCount(2, $productManagementAttributeTransfer->getValues());

        $values = [];
        foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
            $values[] = $productManagementAttributeValueTransfer->getValue();
        }
        $this->tester->assertArrayValuesAreEqual($values, $expectedValues);
    }

    /**
     * @return void
     */
    public function testGetProductManagementAttributeCollectionShouldReturnCollectionWithTwoTransfersWhenLimitAndOffsetApplied(): void
    {
        // Arrange
        for ($i = 0; $i < 4; $i++) {
            $this->tester->haveProductManagementAttributeEntity();
        }

        $productManagementAttributeCriteriaTransfer = (new ProductManagementAttributeCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit(2)->setOffset(1),
            );

        // Act
        $productManagementAttributeCollectionTransfer = $this->tester->getFacade()->getProductManagementAttributeCollection(
            $productManagementAttributeCriteriaTransfer,
        );

        // Assert
        $this->assertCount(2, $productManagementAttributeCollectionTransfer->getProductManagementAttributes());
        $this->assertSame(4, $productManagementAttributeCollectionTransfer->getPagination()->getNbResults());
    }
}
