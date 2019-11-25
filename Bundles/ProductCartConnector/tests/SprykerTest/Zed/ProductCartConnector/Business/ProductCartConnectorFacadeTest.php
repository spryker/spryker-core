<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCartConnector\Business\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCartConnector
 * @group Business
 * @group Plugin
 * @group Facade
 * @group ProductCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductCartConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductCartConnector\ProductCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->tester->setUpDatabase();
    }

    /**
     * @return void
     */
    public function testExpandsCartItemWithExpectedProductUrl(): void
    {
        // Arrange
        $productUrl = $this->tester->createProductUrl();

        $cartChangeTransfer = $this->tester->createCartChangeWithProduct();

        // Act
        $this->tester->getFacade()->expandItemTransfersWithUrl($cartChangeTransfer);

        //Assert
        $this->assertEquals($productUrl->getUrl(), $cartChangeTransfer->getItems()->offsetGet(0)->getUrl());
    }

    /**
     * @return void
     */
    public function testExpandsEmptyCartWithExpectedProductUrl(): void
    {
        // Arrange
        $cartChangeTransfer = new CartChangeTransfer();

        // Act
        $this->tester->getFacade()->expandItemTransfersWithUrl($cartChangeTransfer);

        //Assert
        $this->assertCount(0, $cartChangeTransfer->getItems());
    }
}
