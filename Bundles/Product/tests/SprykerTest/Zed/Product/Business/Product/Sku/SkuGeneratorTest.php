<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Sku;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface;

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
class SkuGeneratorTest extends Unit
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
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected function createSkuGenerator()
    {
        return new SkuGenerator($this->createUtilTextServiceMock());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function createUtilTextServiceMock()
    {
        return $this->getMockBuilder(ProductToUtilTextInterface::class)->getMock();
    }
}
