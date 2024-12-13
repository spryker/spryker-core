<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerTest\Zed\MerchantDiscountConnector\MerchantDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantDiscountConnector
 * @group Business
 * @group Facade
 * @group GetMerchantNamesIndexedByMerchantReferenceTest
 * Add your own group annotations below this line
 */
class GetMerchantNamesIndexedByMerchantReferenceTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_1 = 'test-merchant-reference-1';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE_2 = 'test-merchant-reference-2';

    /**
     * @var \SprykerTest\Zed\MerchantDiscountConnector\MerchantDiscountConnectorBusinessTester
     */
    protected MerchantDiscountConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnAllMerchantNamesIndexedByMerchantReference(): void
    {
        // Arrange
        $merchant1Transfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_1,
        ]);
        $merchant2Transfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE_2,
        ]);

        // Act
        $indexedMerchantNames = $this->tester->getFacade()->getMerchantNamesIndexedByMerchantReference();

        // Assert
        $this->assertContainsEquals($merchant1Transfer->getNameOrFail(), $indexedMerchantNames);
        $this->assertContainsEquals($merchant2Transfer->getNameOrFail(), $indexedMerchantNames);

        $merchantReferences = array_keys($indexedMerchantNames);
        $this->assertContains(static::TEST_MERCHANT_REFERENCE_1, $merchantReferences);
        $this->assertContains(static::TEST_MERCHANT_REFERENCE_2, $merchantReferences);
    }
}
