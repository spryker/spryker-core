<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Sku;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Sku\SkuGenerator;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface;
use SprykerTest\Zed\Product\Business\FacadeTestAbstract;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Sku
 * @group SkuIncrementGeneratorTest
 * Add your own group annotations below this line
 */
class SkuIncrementGeneratorTest extends FacadeTestAbstract
{
    /**
     * @var \SprykerTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateProductConcreteSkuWithoutAttributesAndWithOthersProductsConcreteShouldIncrementMaxSku(): void
    {
        $productConcreteTransfer = $this->tester->haveProduct(['sku' => '9999999' . SkuGenerator::SKU_ABSTRACT_SEPARATOR . '9'], ['sku' => '9999999']);
        $productAbstractTransfer = $this->getFacade()->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());
        $newProductConcreteTransfer = new ProductConcreteTransfer();

        $skuGenerator = $this->createSkuGenerator();
        $formattedSku = $skuGenerator->generateProductConcreteSku($productAbstractTransfer, $newProductConcreteTransfer);

        $this->assertEquals('9999999' . SkuGenerator::SKU_ABSTRACT_SEPARATOR . '10', $formattedSku);
    }

    /**
     * @return void
     */
    public function testGenerateProductConcreteSkuWithoutAttributesAndWithoutOthersProductsConcreteShouldIncrementMaxSku(): void
    {
        $productAbstractTransfer = $this->tester->haveProductAbstract(['sku' => '9999999']);
        $newProductConcreteTransfer = new ProductConcreteTransfer();

        $skuGenerator = $this->createSkuGenerator();
        $formattedSku = $skuGenerator->generateProductConcreteSku($productAbstractTransfer, $newProductConcreteTransfer);

        $this->assertEquals('9999999' . SkuGenerator::SKU_ABSTRACT_SEPARATOR . '1', $formattedSku);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected function createSkuGenerator(): SkuGeneratorInterface
    {
        return new SkuGenerator($this->createUtilTextServiceMock(), $this->createSkuIncrementGeneratorMock());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected function createUtilTextServiceMock(): ProductToUtilTextInterface
    {
        return $this->getMockBuilder(ProductToUtilTextInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGenerator
     */
    protected function createSkuIncrementGeneratorMock(): SkuIncrementGenerator
    {
        return new SkuIncrementGenerator($this->productConcreteManager);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected function getFacade(): ProductFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
