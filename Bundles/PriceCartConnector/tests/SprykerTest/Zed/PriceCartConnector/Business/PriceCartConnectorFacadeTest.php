<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PriceCartConnector
 * @group Business
 * @group Facade
 * @group PriceCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class PriceCartConnectorFacadeTest extends Unit
{

    /**
     * @var \SprykerTest\Zed\PriceCartConnector\PriceCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddPriceToItemsShouldUpdateCartItemsWithPrices()
    {
        $priceCartConnectorFacade = $this->createPriceCartConnectorFacade();

        $productConcreteTransfer =  $this->tester->haveProduct();

        $cartChangeTransfer = new CartChangeTransfer();
        //$cartChangeTransfer->addItem($itemTransfer);

        $updatedCartChangeTransfer = $priceCartConnectorFacade->addPriceToItems($cartChangeTransfer);

    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function createPriceCartConnectorFacade()
    {
        return new PriceCartConnectorFacade();
    }
}
