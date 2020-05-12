<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductOffersRestApi\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProductOffersRestApi
 * @group Business
 * @group Facade
 * @group MerchantProductOffersRestApiFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProductOffersRestApiFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProductOffersRestApi\MerchantProductOffersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\MerchantProductOffersRestApi\Business\MerchantProductOffersRestApiFacadeInterface
     */
    protected $merchantProductOffersRestApiFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->merchantProductOffersRestApiFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testMapCartItemRequestTransferToPersistentCartChangeTransferMapsDataSuccessfully(): void
    {
        // Arrange
        $cartItemRequestTransfer = $this->tester->prepareCartItemRequestTransfer();
        $persistentCartChangeTransfer = $this->tester->preparePersistentCartChangeTransfer();

        // Act
        $changedPersistentCartChangeTransfer = $this->merchantProductOffersRestApiFacade->mapCartItemRequestTransferToPersistentCartChangeTransfer(
            $cartItemRequestTransfer,
            $persistentCartChangeTransfer
        );

        // Assert
        $this->assertEquals(
            $persistentCartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getProductOfferReference()
        );
        $this->assertEquals(
            $persistentCartChangeTransfer->getItems()->getIterator()->current()->getMerchantReference(),
            $changedPersistentCartChangeTransfer->getItems()->getIterator()->current()->getMerchantReference()
        );
    }
}
