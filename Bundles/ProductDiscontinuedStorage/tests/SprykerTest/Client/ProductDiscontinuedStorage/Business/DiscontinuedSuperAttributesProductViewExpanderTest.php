<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductDiscontinuedStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientBridge;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedSuperAttributesProductViewExpander;
use Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedSuperAttributesProductViewExpanderInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReader;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductDiscontinuedStorage
 * @group Business
 * @group DiscontinuedSuperAttributesProductViewExpanderTest
 * Add your own group annotations below this line
 */
class DiscontinuedSuperAttributesProductViewExpanderTest extends Unit
{
    protected const FAKE_ID_PRODUCT_CONCRETE_1 = 6666;
    protected const FAKE_ID_PRODUCT_CONCRETE_2 = 6667;
    protected const FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE = 7777;

    protected const FAKE_SKU_1 = 'fake-sku-1';
    protected const FAKE_SKU_2 = 'fake-sku-2';
    protected const FAKE_DISCONTINUED_SKU = 'fake-discontinued-sku';

    protected const FAKE_SUPER_ATTRIBUTES = [
        'memory' => [
            '4 GB',
            '8 GB',
        ],
    ];

    /**
     * @var \SprykerTest\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandDiscontinuedProductSuperAttributesWithDiscontinuedProduct(): void
    {
        // Arrange
        $attributeMapStorageTransfer = (new AttributeMapStorageTransfer())
            ->setSuperAttributes(static::FAKE_SUPER_ATTRIBUTES)
            ->setAttributeVariantMap([
                static::FAKE_ID_PRODUCT_CONCRETE_1 => [
                    'memory' => '4 GB',
                ],
                static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE => [
                    'memory' => '8 GB',
                ],
            ])
            ->setProductConcreteIds([
                static::FAKE_SKU_1 => static::FAKE_ID_PRODUCT_CONCRETE_1,
                static::FAKE_DISCONTINUED_SKU => static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE,
            ]);

        $productViewTransfer = (new ProductViewTransfer())
            ->setAttributeMap($attributeMapStorageTransfer)
            ->setSelectedAttributes([]);

        // Act
        $productViewTransfer = $this->getProductConcreteStorageReaderMock()
            ->expandDiscontinuedProductSuperAttributes($productViewTransfer, 'DE');

        $attributeVariantMap = $productViewTransfer->getAttributeMapOrFail()->getAttributeVariantMap();
        $superAttributes = $productViewTransfer->getAttributeMapOrFail()->getSuperAttributes();

        // Assert
        $this->assertSame('4 GB', $attributeVariantMap[static::FAKE_ID_PRODUCT_CONCRETE_1]['memory']);
        $this->assertSame('8 GB - Discontinued', $attributeVariantMap[static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE]['memory']);

        $this->assertSame(['memory' => ['4 GB', '8 GB - Discontinued']], $superAttributes);
    }

    /**
     * @return void
     */
    public function testExpandDiscontinuedProductSuperAttributesWithDiscontinuedProductBackwardCompatibilityCheck(): void
    {
        // Arrange
        $attributeMapStorageTransfer = (new AttributeMapStorageTransfer())
            ->setSuperAttributes(static::FAKE_SUPER_ATTRIBUTES)
            ->setAttributeVariants([
                'memory:4 GB' => [
                    'id_product_concrete' => static::FAKE_ID_PRODUCT_CONCRETE_1,
                ],
                'memory:8 GB' => [
                    'id_product_concrete' => static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE,
                ],
            ])
            ->setProductConcreteIds([
                static::FAKE_SKU_1 => static::FAKE_ID_PRODUCT_CONCRETE_1,
                static::FAKE_DISCONTINUED_SKU => static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE,
            ]);

        $productViewTransfer = (new ProductViewTransfer())
            ->setAttributeMap($attributeMapStorageTransfer)
            ->setSelectedAttributes([]);

        // Act
        $productViewTransfer = $this->getProductConcreteStorageReaderMock()
            ->expandDiscontinuedProductSuperAttributes($productViewTransfer, 'DE');

        $attributeVariants = $productViewTransfer->getAttributeMapOrFail()->getAttributeVariants();
        $superAttributes = $productViewTransfer->getAttributeMapOrFail()->getSuperAttributes();

        // Assert
        $this->assertCount(3, $attributeVariants);
        $this->assertSame(static::FAKE_ID_PRODUCT_CONCRETE_1, $attributeVariants['memory:4 GB']['id_product_concrete']);
        $this->assertSame(static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE, $attributeVariants['memory:8 GB']['id_product_concrete']);
        $this->assertSame(static::FAKE_DISCONTINUED_ID_PRODUCT_CONCRETE, $attributeVariants['memory:8 GB - Discontinued']['id_product_concrete']);

        $this->assertSame(['memory' => ['4 GB', '8 GB - Discontinued']], $superAttributes);
    }

    /**
     * @return void
     */
    public function testExpandDiscontinuedProductSuperAttributesWithoutDiscontinuedProduct(): void
    {
        // Arrange
        $attributeMapStorageTransfer = (new AttributeMapStorageTransfer())
            ->setSuperAttributes(static::FAKE_SUPER_ATTRIBUTES)
            ->setAttributeVariantMap([
                static::FAKE_ID_PRODUCT_CONCRETE_1 => [
                    'memory' => '4 GB',
                ],
                static::FAKE_ID_PRODUCT_CONCRETE_2 => [
                    'memory' => '8 GB',
                ],
            ])
            ->setProductConcreteIds([
                static::FAKE_SKU_1 => static::FAKE_ID_PRODUCT_CONCRETE_1,
                static::FAKE_SKU_2 => static::FAKE_ID_PRODUCT_CONCRETE_2,
            ]);

        $productViewTransfer = (new ProductViewTransfer())
            ->setAttributeMap($attributeMapStorageTransfer)
            ->setSelectedAttributes([]);

        // Act
        $productViewTransfer = $this->getProductConcreteStorageReaderMock()
            ->expandDiscontinuedProductSuperAttributes($productViewTransfer, 'DE');

        $attributeVariantMap = $productViewTransfer->getAttributeMapOrFail()->getAttributeVariantMap();
        $superAttributes = $productViewTransfer->getAttributeMapOrFail()->getSuperAttributes();

        // Assert
        $this->assertSame('4 GB', $attributeVariantMap[static::FAKE_ID_PRODUCT_CONCRETE_1]['memory']);
        $this->assertSame('8 GB', $attributeVariantMap[static::FAKE_ID_PRODUCT_CONCRETE_2]['memory']);

        $this->assertSame(['memory' => ['4 GB', '8 GB']], $superAttributes);
    }

    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander\DiscontinuedSuperAttributesProductViewExpanderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductConcreteStorageReaderMock(): DiscontinuedSuperAttributesProductViewExpanderInterface
    {
        return $this
            ->getMockBuilder(DiscontinuedSuperAttributesProductViewExpander::class)
            ->setConstructorArgs([
                $this->getProductDiscontinuedStorageReaderMock(),
                $this->getGlossaryStorageClientMock(),
            ])
            ->onlyMethods([])
            ->getMock();
    }

    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getProductDiscontinuedStorageReaderMock(): ProductDiscontinuedStorageReaderInterface
    {
        $productDiscontinuedStorageReaderMock = $this
            ->getMockBuilder(ProductDiscontinuedStorageReader::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productDiscontinuedStorageReaderMock
            ->method('findProductDiscontinuedStorage')
            ->willReturnCallback(function (string $concreteSku, string $locale) {
                if ($concreteSku === static::FAKE_DISCONTINUED_SKU) {
                    return new ProductDiscontinuedStorageTransfer();
                }

                return null;
            });

        return $productDiscontinuedStorageReaderMock;
    }

    /**
     * @return \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getGlossaryStorageClientMock(): ProductDiscontinuedStorageToGlossaryStorageClientInterface
    {
        $glossaryStorageClientMock = $this
            ->getMockBuilder(ProductDiscontinuedStorageToGlossaryStorageClientBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $glossaryStorageClientMock->method('translate')->willReturn('Discontinued');

        return $glossaryStorageClientMock;
    }
}
