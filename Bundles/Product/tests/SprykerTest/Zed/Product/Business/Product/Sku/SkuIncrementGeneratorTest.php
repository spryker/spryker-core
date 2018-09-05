<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Product\Business\Product\Sku;

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
 * @group SkuIncrementGeneratorTest
 * Add your own group annotations below this line
 */
class SkuIncrementGeneratorTest extends FacadeTestAbstract
{
    /**
     * @return void
     */
    public function testGenerateProductConcreteSkuWithoutAttributesShouldIncrementMaxSku(): void
    {
        $skuGenerator = $this->createSkuGenerator();

        $productAbstractTransfer = $this->productAbstractManager->findProductAbstractById(1);
        $productConcreteIds = $this->productConcreteManager->findProductConcreteIdsByAbstractProductId(1);

        $maxSku = 0;
        foreach ($productConcreteIds as $productConcreteId) {
            $productConcreteTransfer = $this->productConcreteManager->findProductConcreteById($productConcreteId);
            $nextSkuPart = explode('_', $productConcreteTransfer->getSku())[1];

            if ($nextSkuPart > $maxSku) {
                $maxSku = $nextSkuPart;
            }
        }

        $maxSku += 1;

        $productConcreteTransfer = new ProductConcreteTransfer();
        $formattedSku = $skuGenerator->generateProductConcreteSku($productAbstractTransfer, $productConcreteTransfer);

        $this->assertEquals($formattedSku, ($productAbstractTransfer->getSku() . '_' . $maxSku));
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
