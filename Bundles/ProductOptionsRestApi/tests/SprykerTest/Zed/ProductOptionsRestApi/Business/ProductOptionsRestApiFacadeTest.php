<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionsRestApi\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionsRestApi
 * @group Business
 * @group Facade
 * @group ProductOptionsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionsRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOptionsRestApi\ProductOptionsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithOptions(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithOptions();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertNotEmpty(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getProductOptions()
        );

        $idProductOption = $persistentCartChangeTransfer->getItems()
            ->offsetGet(0)
            ->getProductOptions()
            ->offsetGet(0)
            ->getIdProductOptionValue();
        $this->tester->assertEquals(
            $this->tester::ID_PRODUCT_OPTION_VALUE,
            $idProductOption
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithoutOptions(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithoutOptions();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransfer();

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertEmpty(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getProductOptions()
        );
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferWithDifferentSku(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->buildCartItemRequestTransferWithOptions();
        $persistentCartChangeTransfer = $this->tester->buildPersistentCartChangeTransferWithDifferentSku();

        // Act
        $persistentCartChangeTransfer = $this->tester
            ->getFacade()
            ->mapCartItemRequestTransferToPersistentCartChangeTransfer(
                $cartItemRequestTransfer,
                $persistentCartChangeTransfer
            );

        // Assert
        $this->tester->assertEmpty(
            $persistentCartChangeTransfer->getItems()
                ->offsetGet(0)
                ->getProductOptions()
        );
    }
}
