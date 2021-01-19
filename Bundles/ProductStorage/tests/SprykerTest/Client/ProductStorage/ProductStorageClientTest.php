<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductStorage;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AttributeMapStorageBuilder;
use Generated\Shared\DataBuilder\ProductAbstractStorageBuilder;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface;
use Spryker\Client\ProductStorage\Mapper\ProductVariantExpander;
use Spryker\Client\ProductStorage\ProductStorageDependencyProvider;
use Spryker\Client\ProductStorage\ProductStorageFactory;
use Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductStorage
 * @group ProductStorageClientTest
 * Add your own group annotations below this line
 */
class ProductStorageClientTest extends Unit
{
    protected const SUPER_ATTRIBUTE_NAME_1 = 'super_attribute_name_1';
    protected const SUPER_ATTRIBUTE_NAME_2 = 'super_attribute_name_2';
    protected const SUPER_ATTRIBUTE_VALUE_1 = 'super_attribute_value_1';
    protected const SUPER_ATTRIBUTE_VALUE_2_1 = 'super_attribute_value_2_1';
    protected const SUPER_ATTRIBUTE_VALUE_2_2 = 'super_attribute_value_2_2';

    /**
     * @var \SprykerTest\Client\ProductStorage\ProductStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleNameReturnsCorrectData(): void
    {
        // Arrange
        $productAbstractStorageTransfer = $this->getProductAbstractStorage();
        $idProductAbstract = $productAbstractStorageTransfer->getIdProductAbstract();
        $localeName = 'DE';
        $storeName = 'DE';

        $this->getStorageClientMock()
            ->expects($this->once())
            ->method('getMulti')
            ->willReturn([
                json_encode($productAbstractStorageTransfer->toArray()),
            ]);

        // Act
        $productAbstractStorageData = $this->tester
            ->getProductStorageClient()
            ->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore([$idProductAbstract], $localeName, $storeName);

        // Assert
        $this->assertCount(1, $productAbstractStorageData);
        $this->assertArrayHasKey($idProductAbstract, $productAbstractStorageData);
        $this->assertSame($productAbstractStorageTransfer->toArray(), $productAbstractStorageData[$idProductAbstract]);
    }

    /**
     * @return void
     */
    public function testExpandProductVariantDataMergeAbstractAndConcreteArrayFilterDoesNotRemoveFalse(): void
    {
        // Arrange
        $productViewTransfer = $this->tester->createProductViewTransfer();
        $productConcreteStorageReaderMock = $this->getProductConcreteStorageReaderMock();

        // Act
        $productConcreteStorageData = (new ProductVariantExpander($productConcreteStorageReaderMock))
            ->expandProductVariantData($productViewTransfer, 'DE');

        // Assert
        $this->assertFalse($productConcreteStorageData[ProductViewTransfer::AVAILABLE]);
    }

    /**
     * @return void
     */
    public function testExpandProductViewWithProductVariantMergeAbstractAndConcreteArrayFilterDoesNotRemoveFalse(): void
    {
        // Arrange
        $productViewTransfer = $this->tester->createProductViewTransfer();
        $productConcreteStorageReaderMock = $this->getProductConcreteStorageReaderMock();

        // Act
        $expandedProductViewTransfer = (new ProductVariantExpander($productConcreteStorageReaderMock))
            ->expandProductViewWithProductVariant($productViewTransfer, 'DE');

        // Assert
        $this->assertFalse($expandedProductViewTransfer->getAvailable());
    }

    /**
     * @dataProvider superAttributesDataProvider
     *
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     * @param string[] $originalSelectedAttributes
     * @param mixed[] $expectedSelectedAttributes
     *
     * @return void
     */
    public function testExpandProductViewWithProductVariantSelectsProductVariantsWithSingleValue(
        AttributeMapStorageTransfer $attributeMapStorageTransfer,
        array $originalSelectedAttributes,
        array $expectedSelectedAttributes
    ): void {
        // Arrange
        $productViewTransfer = $this->tester->createProductViewTransfer();
        $productConcreteStorageReaderMock = $this->getProductConcreteStorageReaderMock();
        $productViewTransfer->setAttributeMap($attributeMapStorageTransfer);
        $productViewTransfer->setSelectedAttributes($originalSelectedAttributes);

        // Act
        $expandedProductViewTransfer = (new ProductVariantExpander($productConcreteStorageReaderMock))
            ->expandProductViewWithProductVariant($productViewTransfer, 'DE');

        // Assert
        $this->assertSame($expectedSelectedAttributes, $expandedProductViewTransfer->getSelectedAttributes());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductStorage\Dependency\Client\ProductStorageToStorageClientInterface
     */
    protected function getStorageClientMock(): ProductStorageToStorageClientInterface
    {
        $storageClientMock = $this->getMockBuilder(ProductStorageToStorageClientInterface::class)->getMock();
        $this->tester->setDependency(
            ProductStorageDependencyProvider::CLIENT_STORAGE,
            $storageClientMock,
            ProductStorageFactory::class
        );

        return $storageClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\ProductStorage\Storage\ProductConcreteStorageReaderInterface
     */
    protected function getProductConcreteStorageReaderMock(): ProductConcreteStorageReaderInterface
    {
        $productConcreteStorageReaderMock = $this->getMockBuilder(ProductConcreteStorageReaderInterface::class)->getMock();
        $productConcreteStorageReaderMock
            ->method('findProductConcreteStorageData')
            ->willReturn([ProductViewTransfer::AVAILABLE => false]);

        return $productConcreteStorageReaderMock;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    protected function getProductAbstractStorage(array $seedData = []): ProductAbstractStorageTransfer
    {
        return (new ProductAbstractStorageBuilder($seedData))->build();
    }

    /**
     * @param mixed[] $seedData
     *
     * @return \Generated\Shared\Transfer\AttributeMapStorageTransfer
     */
    protected function buildAttributeMapStorageTransfer(array $seedData): AttributeMapStorageTransfer
    {
        return (new AttributeMapStorageBuilder($seedData))->build();
    }

    /**
     * @return array[]
     */
    public function superAttributesDataProvider(): array
    {
        return [
            'super attributes with single and multiple values and existing selected attribute' => [
                $this->buildAttributeMapStorageTransfer([
                    AttributeMapStorageTransfer::SUPER_ATTRIBUTES => [
                        static::SUPER_ATTRIBUTE_NAME_1 => [
                            static::SUPER_ATTRIBUTE_VALUE_1,
                        ],
                        static::SUPER_ATTRIBUTE_NAME_2 => [
                            static::SUPER_ATTRIBUTE_VALUE_2_1,
                            static::SUPER_ATTRIBUTE_VALUE_2_2,
                        ],
                    ],
                ]),
                [
                    static::SUPER_ATTRIBUTE_NAME_2 => static::SUPER_ATTRIBUTE_VALUE_2_1,
                ],
                [
                    static::SUPER_ATTRIBUTE_NAME_1 => static::SUPER_ATTRIBUTE_VALUE_1,
                    static::SUPER_ATTRIBUTE_NAME_2 => static::SUPER_ATTRIBUTE_VALUE_2_1,
                ],
            ],
            'super attributes with single and multiple values without existing selected attribute' => [
                $this->buildAttributeMapStorageTransfer([
                    AttributeMapStorageTransfer::SUPER_ATTRIBUTES => [
                        static::SUPER_ATTRIBUTE_NAME_1 => [
                            static::SUPER_ATTRIBUTE_VALUE_1,
                        ],
                        static::SUPER_ATTRIBUTE_NAME_2 => [
                            static::SUPER_ATTRIBUTE_VALUE_2_1,
                            static::SUPER_ATTRIBUTE_VALUE_2_2,
                        ],
                    ],
                ]),
                [],
                [
                    static::SUPER_ATTRIBUTE_NAME_1 => static::SUPER_ATTRIBUTE_VALUE_1,
                ],
            ],
            'super attributes with only single value' => [
                $this->buildAttributeMapStorageTransfer([
                    AttributeMapStorageTransfer::SUPER_ATTRIBUTES => [
                        static::SUPER_ATTRIBUTE_NAME_1 => [
                            static::SUPER_ATTRIBUTE_VALUE_1,
                        ],
                    ],
                ]),
                [],
                [
                    static::SUPER_ATTRIBUTE_NAME_1 => static::SUPER_ATTRIBUTE_VALUE_1,
                ],
            ],
            'super attributes with only multiple values' => [
                $this->buildAttributeMapStorageTransfer([
                    AttributeMapStorageTransfer::SUPER_ATTRIBUTES => [
                        static::SUPER_ATTRIBUTE_NAME_2 => [
                            static::SUPER_ATTRIBUTE_VALUE_2_1,
                            static::SUPER_ATTRIBUTE_VALUE_2_2,
                        ],
                    ],
                ]),
                [],
                [],
            ],
            'super attributes are empty' => [
                $this->buildAttributeMapStorageTransfer([]),
                [],
                [],
            ],
        ];
    }
}
