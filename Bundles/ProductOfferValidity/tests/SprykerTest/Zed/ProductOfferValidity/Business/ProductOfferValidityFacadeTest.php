<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferValidity\Business;

use Codeception\Test\Unit;
use DateTime;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOfferValidity
 * @group Business
 * @group Facade
 * @group ProductOfferValidityFacadeTest
 * Add your own group annotations below this line
 */
class ProductOfferValidityFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductOfferValidity\ProductOfferValidityBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->truncateProductOfferValidities();
    }

    /**
     * @return void
     */
    public function testCheckProductOfferValidityDateRange(): void
    {
        // Arrange
        $merchant = $this->tester->haveMerchant();

        $productOfferValid = $this->tester->haveProductOffer([
            'fkMerchant' => $merchant->getIdMerchant(),
            'isActive' => false,
        ]);

        $productOfferInvalid = $this->tester->haveProductOffer([
            'fkMerchant' => $merchant->getIdMerchant(),
            'isActive' => true,
        ]);

        $this->tester->haveProductOfferValidity([
            'idProductOffer' => $productOfferValid->getIdProductOffer(),
            'validFrom' => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
            'validTo' => (new DateTime('tomorrow'))->format('Y-m-d H:i:s'),
        ]);

        $this->tester->haveProductOfferValidity([
            'idProductOffer' => $productOfferInvalid->getIdProductOffer(),
            'validFrom' => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
            'validTo' => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
        ]);

        // Act
        $this->tester->getFacade()->checkProductOfferValidityDateRange();

        $productOfferValid = $this->tester->findProductOfferById($productOfferValid->getIdProductOffer());
        $productOfferInvalid = $this->tester->findProductOfferById($productOfferInvalid->getIdProductOffer());

        // Assert
        $this->assertTrue($productOfferValid->getIsActive());
        $this->assertFalse($productOfferInvalid->getIsActive());
    }
}
