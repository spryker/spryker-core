<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Persistence\Mapper;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductClassCollectionTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClass;
use SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\ProductClassMapper;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Persistence
 * @group Mapper
 * @group ProductClassMapperTest
 */
class ProductClassMapperTest extends Unit
{
    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper\ProductClassMapper
     */
    protected ProductClassMapper $productClassMapper;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->productClassMapper = new ProductClassMapper();
    }

    /**
     * @return void
     */
    public function testMapProductClassEntityToProductClassTransfer(): void
    {
        // Arrange
        $productClassEntity = $this->createProductClassEntity();

        // Act
        $productClassTransfer = $this->productClassMapper->mapProductClassEntityToProductClassTransfer($productClassEntity, new ProductClassTransfer());

        // Assert
        $this->assertInstanceOf(ProductClassTransfer::class, $productClassTransfer);
        $this->assertSame(1, $productClassTransfer->getIdProductClass());
        $this->assertSame('test-class', $productClassTransfer->getName());
    }

    /**
     * @return void
     */
    public function testMapProductClassEntitiesToProductClassCollectionTransfer(): void
    {
        // Arrange
        $productClassEntity1 = $this->createProductClassEntity();
        $productClassEntity2 = $this->createProductClassEntity(2, 'test-class-2');

        $productClassEntities = [$productClassEntity1, $productClassEntity2];
        $productClassCollectionTransfer = new ProductClassCollectionTransfer();

        // Act
        $resultCollectionTransfer = $this->productClassMapper->mapProductClassEntitiesToProductClassCollectionTransfer(
            $productClassEntities,
            $productClassCollectionTransfer,
        );

        // Assert
        $this->assertInstanceOf(ProductClassCollectionTransfer::class, $resultCollectionTransfer);
        $this->assertInstanceOf(ArrayObject::class, $resultCollectionTransfer->getProductClasses());
        $this->assertCount(2, $resultCollectionTransfer->getProductClasses());
        $this->assertSame(1, $resultCollectionTransfer->getProductClasses()[0]->getIdProductClass());
        $this->assertSame('test-class', $resultCollectionTransfer->getProductClasses()[0]->getName());
        $this->assertSame(2, $resultCollectionTransfer->getProductClasses()[1]->getIdProductClass());
        $this->assertSame('test-class-2', $resultCollectionTransfer->getProductClasses()[1]->getName());
    }

    /**
     * @param int $idProductClass
     * @param string $name
     *
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductClass
     */
    protected function createProductClassEntity(int $idProductClass = 1, string $name = 'test-class'): SpyProductClass
    {
        $productClassEntityMock = $this->getMockBuilder(SpyProductClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productClassEntityMock
            ->method('getIdProductClass')
            ->willReturn($idProductClass);

        $productClassEntityMock
            ->method('getName')
            ->willReturn($name);

        $productClassEntityMock
            ->method('toArray')
            ->willReturn([
                'id_product_class' => $idProductClass,
                'name' => $name,
            ]);

        return $productClassEntityMock;
    }
}
