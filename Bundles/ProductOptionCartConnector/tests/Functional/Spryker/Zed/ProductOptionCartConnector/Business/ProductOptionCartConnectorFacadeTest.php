<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductOptionCartConnector\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade;

/**
 * @group Business
 * @group Zed
 * @group ProductOptionCartConnector
 * @group ProductOptionCartConnectorFacadeTest
 */
class ProductOptionCartConnectorFacadeTest extends Test
{

    const LOCALE_CODE = 'xx_XX';

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Business\ProductOptionCartConnectorFacade
     */
    private $facade;

    /**
     * @var array
     */
    protected $ids = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->facade = new ProductOptionCartConnectorFacade();
        //$this->ids = DbFixturesLoader::loadFixtures();
    }

    /**
     * @return void
     */
    public function testExpandProductOption()
    {
        $productOptionTransfer = (new ProductOptionTransfer())
            ->setIdProductOptionValue($this->ids['idUsageLarge'])
            ->setLocaleCode(self::LOCALE_CODE);

        $itemTransfer = (new ItemTransfer())
            ->addProductOption($productOptionTransfer);

        $changeTransfer = (new CartChangeTransfer())
            ->addItem($itemTransfer);

        $this->facade->expandProductOptions($changeTransfer);

        $productOptionTransfer = $changeTransfer->getItems()[0]->getProductOptions()[0];

        /*$this->assertEquals($this->ids['idUsageLarge'], $productOptionTransfer->getIdOptionValueUsage());
        $this->assertEquals(self::LOCALE_CODE, $productOptionTransfer->getLocaleCode());
        $this->assertEquals('Size', $productOptionTransfer->getGroupName());
        $this->assertEquals('Large', $productOptionTransfer->getValue());
        $this->assertEquals(199, $productOptionTransfer->getUnitGrossPrice());*/
    }

}
