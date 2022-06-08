<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductValidity\ProductValidityFacade;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductValidity\ProductValidityConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductValidity
 * @group ProductValidityFacade
 * @group ExpandProductConcreteTransfersWithValidityTest
 * Add your own group annotations below this line
 */
class ExpandProductConcreteTransfersWithValidityTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductValidity\ProductValidityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandProductConcreteTransfersWithValiditySuccessful(): void
    {
        // Arrange
        $validForm = new DateTime();
        $validTo = new DateTime('+1 day');

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $productWithValidityTransfer = $this->tester->haveProductConcrete([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);
        $this->tester->haveValidity(
            $productWithValidityTransfer->getIdProductConcreteOrFail(),
            $validForm,
            $validTo,
        );

        $productWithoutValidityTransfer = $this->tester->haveProductConcrete([
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
        ]);

        // Act
        $productTransfers = $this->tester->getLocator()->productValidity()->facade()
            ->expandProductConcreteTransfersWithValidity(
                [
                    $productWithValidityTransfer,
                    $productWithoutValidityTransfer,
                ],
            );

        // Assert
        $this->assertSame($validForm->format(ProductValidityConfig::VALIDITY_DATE_TIME_FORMAT), $productTransfers[0]->getValidFromOrFail());
        $this->assertSame($validTo->format(ProductValidityConfig::VALIDITY_DATE_TIME_FORMAT), $productTransfers[0]->getValidToOrFail());
        $this->assertNull($productTransfers[1]->getValidFrom());
        $this->assertNull($productTransfers[1]->getValidTo());
    }
}
