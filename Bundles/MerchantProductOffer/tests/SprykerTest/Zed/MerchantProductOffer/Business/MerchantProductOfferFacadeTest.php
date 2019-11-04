<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffer\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductOfferTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffer
 * @group Business
 * @group Facade
 * @group MerchantProductOfferFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOfferFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffer\MerchantProductOfferBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindIdMerchantWithExistingProductOfferReference(): void
    {
        //Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProductOfferTransfer = $this->tester->haveProductOffer([
            ProductOfferTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
        ]);

        //Act
        $idMerchant = $this->tester->getLocator()->merchantProductOffer()->facade()->findIdMerchantByProductOfferReference($merchantProductOfferTransfer->getProductOfferReference());

        //Assert
        $this->assertEquals($idMerchant, $merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testFindIdMerchantWithNotExistingProductOfferReference(): void
    {
        //Act
        $idMerchant = $this->tester->getLocator()->merchantProductOffer()->facade()->findIdMerchantByProductOfferReference(uniqid());

        //Assert
        $this->assertNull($idMerchant);
    }
}
