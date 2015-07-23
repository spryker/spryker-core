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
use Functional\SprykerFeature\Zed\ProductOption\Persistence\DbFixturesLoader;

/**
 * @group Business
 * @group Zed
 * @group ProductOptionCartConnector
 * @group ProductOptionCartConnectorFacadeTest
 */
class ProductOptionCartConnectorFacadeTest extends AbstractFunctionalTest
{

    const LOCALE_CODE = 'xx_XX';

    /**
     * @var ProductOptionCartConnectorFacade
     */
    private $facade;

    /**
     * @var array
     */
    protected $ids = [];

    public function setUp()
    {
        parent::setUp();

        $this->facade = $this->getFacade();
        $this->ids = DbFixturesLoader::loadFixtures();
    }

    public function testExpandProductOption()
    {
        $productOptionTransfer = (new ProductOptionTransfer)
            ->setIdOptionValueUsage($this->ids['idUsageLarge'])
            ->setLocalCode(self::LOCALE_CODE);

        $cartItemTransfer = (new CartItemTransfer())
            ->addProductOption($productOptionTransfer);

        $changeTransfer = (new ChangeTransfer())
            ->addItem($cartItemTransfer);

        $this->facade->expandProductOptions($changeTransfer);

        $productOptionTransfer = $changeTransfer->getItems()[0]->getProductOptions()[0];

        $this->assertEquals($this->ids['idUsageLarge'], $productOptionTransfer->getIdOptionValueUsage());
        $this->assertEquals(self::LOCALE_CODE, $productOptionTransfer->getLocalCode());
        $this->assertEquals('Size', $productOptionTransfer->getLabelOptionType());
        $this->assertEquals('Large', $productOptionTransfer->getLabelOptionValue());
        $this->assertEquals(199, $productOptionTransfer->getPrice());

        $taxSetTransfer = $productOptionTransfer->getTaxSet();

        $this->assertEquals('Baz', $taxSetTransfer->getName());

        $taxRateTransfer = $taxSetTransfer->getTaxRates()[0];
        $this->assertEquals('Foo', $taxRateTransfer->getName());
        $this->assertEquals('10', $taxRateTransfer->getRate());
    }
}
