<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternative\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternative
 * @group Business
 * @group Facade
 * @group ProductAlternativeFacadeTest
 * Add your own group annotations below this line
 */
class ProductAlternativeFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAlternative\ProductAlternativeBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProductCanHaveAlternativeAbstractProduct(): void
    {
        // Arrange
        $targetProductConcrete = $this->tester->haveProduct();
        $alternativeProductConcrete = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($targetProductConcrete->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductConcrete->getAbstractSku());
        $targetProductConcrete->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);

        // Act
        $this->tester->getFacade()->persistProductAlternative($targetProductConcrete);
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()
            ->getProductAlternativeListByIdProductConcrete($targetProductConcrete->getIdProductConcrete());

        // Assert
        $this->assertCount(1, $productDiscontinuedResponseTransfer->getProductAlternatives());
    }

    /**
     * @return void
     */
    public function testProductCanHaveAlternativeConcreteProduct(): void
    {
        // Arrange
        $targetProductConcrete = $this->tester->haveProduct();
        $alternativeProductConcrete = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($targetProductConcrete->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductConcrete->getSku());
        $targetProductConcrete->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);

        // Act
        $this->tester->getFacade()->persistProductAlternative($targetProductConcrete);
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()
            ->getProductAlternativeListByIdProductConcrete($targetProductConcrete->getIdProductConcrete());

        // Assert
        $this->assertCount(1, $productDiscontinuedResponseTransfer->getProductAlternatives());
    }

    /**
     * @return void
     */
    public function testAlternativeProductCanBeRemoved(): void
    {
        // Arrange
        $targetProductConcrete = $this->tester->haveProduct();
        $alternativeProductConcrete = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($targetProductConcrete->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductConcrete->getSku());
        $targetProductConcrete->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);

        // Act
        $this->tester->getFacade()->persistProductAlternative($targetProductConcrete);
        $productAlternativeTransfer = $this->tester->getFacade()
            ->getProductAlternativeListByIdProductConcrete($targetProductConcrete->getIdProductConcrete())
            ->getProductAlternatives()[0];
        $this->tester->getFacade()->deleteProductAlternativeByIdProductAlternative($productAlternativeTransfer->getIdProductAlternative());
        $productDiscontinuedResponseTransfer = $this->tester->getFacade()
            ->getProductAlternativeListByIdProductConcrete($targetProductConcrete->getIdProductConcrete());

        // Assert
        $this->assertCount(0, $productDiscontinuedResponseTransfer->getProductAlternatives());
    }
}
