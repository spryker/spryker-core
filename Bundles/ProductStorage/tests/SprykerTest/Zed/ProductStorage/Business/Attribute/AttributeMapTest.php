<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Business\Attribute;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\Collection\ObjectCollection;
use ReflectionProperty;
use Spryker\Zed\ProductStorage\Business\Attribute\AttributeMap;
use Spryker\Zed\ProductStorage\Business\Filter\SingleValueSuperAttributeFilter;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface;
use Spryker\Zed\ProductStorage\ProductStorageConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Business
 * @group Attribute
 * @group AttributeMapTest
 * Add your own group annotations below this line
 */
class AttributeMapTest extends Unit
{
    protected const FAKE_SKU_1 = 'fake-sku-1';
    protected const FAKE_SKU_2 = 'fake-sku-2';

    protected const FAKE_PRODUCT_ATTRIBUTES_1 = [
        'attribute_1' => 'value_1_1',
        'attribute_2' => 'value_1_2',
    ];

    protected const FAKE_PRODUCT_ATTRIBUTES_2 = [
        'attribute_1' => 'value_2_1',
        'attribute_2' => 'value_2_2',
    ];

    protected const FAKE_SUPER_ATTRIBUTES = [
        'attribute_1', 'attribute_2', 'attribute_3', 'attribute_4', 'attribute_5', 'attribute_6',
    ];

    protected const KEY_ID_PRODUCT = 'spy_product.id_product';
    protected const KEY_ATTRIBUTES = 'spy_product.attributes';
    protected const KEY_SKU = 'spy_product.sku';
    protected const KEY_FK_PRODUCT_ABSTRACT = 'spy_product.fk_product_abstract';
    protected const KEY_FK_LOCALE = 'fk_locale';
    protected const KEY_LOCALIZED_ATTRIBUTES = 'localized_attributes';

    /**
     * @return void
     */
    public function testGenerateAttributeMapBulkWillGenerateAttributeVariantMap(): void
    {
        // Arrange
        $productConcreteData1 = [
            static::KEY_ID_PRODUCT => 1,
            static::KEY_ATTRIBUTES => json_encode(static::FAKE_PRODUCT_ATTRIBUTES_1),
            static::KEY_SKU => static::FAKE_SKU_1,
            static::KEY_FK_PRODUCT_ABSTRACT => 1,
            static::KEY_LOCALIZED_ATTRIBUTES => '{}',
            static::KEY_FK_LOCALE => 64,
        ];

        $productConcreteData2 = [
            static::KEY_ID_PRODUCT => 2,
            static::KEY_ATTRIBUTES => json_encode(static::FAKE_PRODUCT_ATTRIBUTES_2),
            static::KEY_SKU => static::FAKE_SKU_2,
            static::KEY_FK_PRODUCT_ABSTRACT => 1,
            static::KEY_LOCALIZED_ATTRIBUTES => '{}',
            static::KEY_FK_LOCALE => 64,
        ];

        $expectedAttributeVariantMap = [
            '1' => static::FAKE_PRODUCT_ATTRIBUTES_1,
            '2' => static::FAKE_PRODUCT_ATTRIBUTES_2,
        ];

        $productConcreteDataList = [$productConcreteData1, $productConcreteData2];
        $productStorageQueryContainerMock = $this->createProductStorageQueryContainerMock(
            $productConcreteDataList,
            static::FAKE_SUPER_ATTRIBUTES
        );

        $reflection = new ReflectionProperty(AttributeMap::class, 'superAttributesCache');
        $reflection->setAccessible(true);
        $reflection->setValue(null, null);

        $attributeMap = new AttributeMap(
            $this->createProductFacadeMock([static::FAKE_PRODUCT_ATTRIBUTES_1, static::FAKE_PRODUCT_ATTRIBUTES_2]),
            $productStorageQueryContainerMock,
            $this->createProductStorageConfigMock(false),
            new SingleValueSuperAttributeFilter()
        );

        // Act
        $attributeMapBulk = $attributeMap->generateAttributeMapBulk([1], [64]);

        // Assert
        $this->assertCount(1, $attributeMapBulk);
        $this->assertArrayHasKey('1_64', $attributeMapBulk);
        /** @var \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer */
        $attributeMapStorageTransfer = $attributeMapBulk['1_64'];
        $this->assertInstanceOf(AttributeMapStorageTransfer::class, $attributeMapStorageTransfer);

        $this->assertCount(2, $attributeMapStorageTransfer->getAttributeVariantMap());
        $this->assertEqualsCanonicalizing($expectedAttributeVariantMap, $attributeMapStorageTransfer->getAttributeVariantMap());
    }

    /**
     * @param array $productAttributesCombined
     *
     * @return \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductFacadeMock(array $productAttributesCombined = []): ProductStorageToProductInterface
    {
        $productFacadeMock = $this->getMockBuilder(ProductStorageToProductInterface::class)->getMock();
        $productFacadeMock->method('decodeProductAttributes')->willReturn([]);
        $productFacadeMock->method('combineRawProductAttributes')->willReturnOnConsecutiveCalls(...$productAttributesCombined);

        return $productFacadeMock;
    }

    /**
     * @param array $productConcreteEntitiesData
     * @param array $superAttributesData
     *
     * @return \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductStorageQueryContainerMock(
        array $productConcreteEntitiesData,
        array $superAttributesData
    ): ProductStorageQueryContainerInterface {
        $productStorageQueryContainerMock = $this->getMockBuilder(ProductStorageQueryContainerInterface::class)->getMock();
        $productStorageQueryContainerMock
            ->method('queryConcreteProductBulk')
            ->willReturn($this->createSpyProductQueryMock($productConcreteEntitiesData));
        $productStorageQueryContainerMock
            ->method('queryProductAttributeKey')
            ->willReturn($this->createSpyProductAttributeKeyQueryMock($superAttributesData));

        return $productStorageQueryContainerMock;
    }

    /**
     * @param array $productConcreteEntitiesData
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createSpyProductQueryMock(array $productConcreteEntitiesData): SpyProductQuery
    {
        $spyProductQueryMock = $this->getMockBuilder(SpyProductQuery::class)->getMock();
        $spyProductQueryMock
            ->method('find')
            ->willReturn($this->createObjectCollectionMock($productConcreteEntitiesData));

        return $spyProductQueryMock;
    }

    /**
     * @param array $superAttributesData
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAttributeKeyQuery
     */
    protected function createSpyProductAttributeKeyQueryMock(array $superAttributesData): SpyProductAttributeKeyQuery
    {
        $spyProductAttributeKeyQueryMock = $this->getMockBuilder(SpyProductAttributeKeyQuery::class)->getMock();
        $spyProductAttributeKeyQueryMock->method('select')->willReturnSelf();
        $spyProductAttributeKeyQueryMock
            ->method('find')
            ->willReturn($this->createObjectCollectionMock($superAttributesData));

        return $spyProductAttributeKeyQueryMock;
    }

    /**
     * @param array $data
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createObjectCollectionMock(array $data): ObjectCollection
    {
        $objectCollectionMock = $this->getMockBuilder(ObjectCollection::class)->getMock();
        $objectCollectionMock->method('toArray')->willReturn($data);

        return $objectCollectionMock;
    }

    /**
     * @param bool $isAttributeVariantsMapEnabled
     * @param bool $isProductAttributesWithSingleValueIncluded
     *
     * @return \Spryker\Zed\ProductStorage\ProductStorageConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createProductStorageConfigMock(
        bool $isAttributeVariantsMapEnabled,
        bool $isProductAttributesWithSingleValueIncluded = true
    ): ProductStorageConfig {
        $productStorageConfigMock = $this->getMockBuilder(ProductStorageConfig::class)->getMock();
        $productStorageConfigMock
            ->method('isProductAttributesWithSingleValueIncluded')
            ->willReturn($isProductAttributesWithSingleValueIncluded);
        $productStorageConfigMock
            ->method('isAttributeVariantsMapEnabled')
            ->willReturn($isAttributeVariantsMapEnabled);

        return $productStorageConfigMock;
    }
}
