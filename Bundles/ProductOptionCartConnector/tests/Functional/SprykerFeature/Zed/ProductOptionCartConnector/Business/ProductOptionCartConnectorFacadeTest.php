<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOptionCartConnector\Business;

use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerFeature\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

/**
 * @group Business
 * @group Zed
 * @group ProductOptionCartConnector
 * @group ProductOptionCartConnectorFacadeTest
 */
class ProductOptionCartConnectorFacadeTest extends AbstractFunctionalTest
{

    /**
     * @var ProductOptionCartConnectorFacade
     */
    private $facade;

    public function setUp()
    {
        parent::setUp();

        $this->facade = $this->getFacade();
    }

    public function testIteWorks()
    {
        $productOptionTransfer = (new ProductOptionTransfer)
            ->setIdOptionValueUsage(2)
            ->setFkLocale(58);

        $cartItemTransfer = (new CartItemTransfer())
            ->addProductOption($productOptionTransfer);

        $changeTransfer = (new ChangeTransfer())
            ->addItem($cartItemTransfer);


        $this->facade->expandProductOptions($changeTransfer);

        $result = $changeTransfer->getItems()[0]->getProductOptions()[0];
        $taxSet = $result->getTaxSet();
        $taxRate = $taxSet->getTaxRates()[0];

        // @TODO: Load fixtures and perform asertions
    }
}
