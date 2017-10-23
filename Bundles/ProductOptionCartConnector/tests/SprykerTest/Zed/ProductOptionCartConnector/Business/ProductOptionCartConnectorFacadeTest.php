<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOptionCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductOptionCartConnector
 * @group Business
 * @group Facade
 * @group ProductOptionCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductOptionCartConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOptionCartConnector\ProductOptionCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCheckProductOptionExistsShouldReturnSuccessWhenItemsPresent()
    {
        $productOptionGroupTransfer = $this->tester->haveProductOption();

        $productOptionCartConnectorFacade = $this->createProductOptionCartConnectorFacade();

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();

        foreach ($productOptionGroupTransfer->getProductOptionValues() as $productOptionValueTransfer) {
            $productOptionTransfer = new ProductOptionTransfer();
            $productOptionTransfer->setIdProductOptionValue($productOptionValueTransfer->getIdProductOptionValue());
            $itemTransfer->addProductOption($productOptionTransfer);
            $cartChangeTransfer->addItem($itemTransfer);
        }

        $cartPreCheckResponseTransfer = $productOptionCartConnectorFacade->checkProductOptionExists($cartChangeTransfer);

        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckProductOptionExistsShouldWriteErrorWhenOptionDoesNotExist()
    {
        $productOptionCartConnectorFacade = $this->createProductOptionCartConnectorFacade();

        $cartChangeTransfer = new CartChangeTransfer();

        $itemTransfer = new ItemTransfer();

        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->setIdProductOptionValue(0);
        $itemTransfer->addProductOption($productOptionTransfer);

        $cartChangeTransfer->addItem($itemTransfer);

        $cartPreCheckResponseTransfer = $productOptionCartConnectorFacade->checkProductOptionExists($cartChangeTransfer);

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacadeInterface
     */
    protected function createProductOptionCartConnectorFacade()
    {
        return new ProductOptionCartConnectorFacade();
    }
}
