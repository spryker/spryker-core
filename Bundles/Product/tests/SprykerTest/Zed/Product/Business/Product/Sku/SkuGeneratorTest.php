<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Sku;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface;
use SprykerTest\Zed\Product\Business\FacadeTestAbstract;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Sku
 * @group SkuGeneratorTest
 * Add your own group annotations below this line
 */
class SkuGeneratorTest extends FacadeTestAbstract
{
    /**
     * @return void
     */
    public function testGenerateProductAbstractSkuShouldSanitizeSku()
    {
        $skuGenerator = $this->createSkuGenerator();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('one-ONE    ONE----Lietuviškai');

        $sanitizedSku = $skuGenerator->generateProductAbstractSku($productAbstractTransfer);

        $this->assertEquals('one-ONEONE-Lietuviskai', $sanitizedSku);
    }

    /**
     * @return void
     */
    public function testGenerateProductConcreteSkuShouldSanitizeAndConcatSku()
    {
        $skuGenerator = $this->createSkuGenerator();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('one-ONE    ONE----Lietuviškai');

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([
            'key' => 'value',
            'key2' => 'value2',
        ]);

        $sanitizedSku = $skuGenerator->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);

        $this->assertEquals('one-ONEONE-Lietuviskai-key-value_key2-value2', $sanitizedSku);
    }

    /**
     * @return void
     */
    public function testGenerateProductConcreteSkuWithManyAttributesShouldTruncatesToMaxSkuLength(): void
    {
        $skuGenerator = $this->createSkuGenerator();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->setSku('Long Sku');

        $productConcreteTransfer = new ProductConcreteTransfer();
        $productConcreteTransfer->setAttributes([
            'color' => 'blue',
            'flash_memory' => '4GB',
            'form_factor' => 'Bar',
            'internal_memory' => '32GB',
            'internal_storage_capacity' => '1526MB',
            'os_installed' => 'Android',
            'processor_cache' => '4MB',
            'processor_frequency' => '1.6GHz',
            'series' => 'Ace2',
            'storage_capacity' => '128GB',
            'storage_media' => 'SSD',
            'total-megapixels' => '16.1MP',
            'total_storage_capacity' => '128GB',
        ]);

        $formattedSku = $skuGenerator->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);

        $this->assertTrue(strlen($formattedSku) <= SkuGenerator::SKU_MAX_LENGTH);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected function createSkuGenerator()
    {
        return new SkuGenerator($this->createUtilTextServiceMock(), $this->createSkuIncrementGeneratorMock());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function createUtilTextServiceMock()
    {
        return $this->getMockBuilder(ProductToUtilTextInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator
     */
    protected function createSkuIncrementGeneratorMock()
    {
        return new SkuIncrementGenerator($this->productConcreteManager);
    }
}
