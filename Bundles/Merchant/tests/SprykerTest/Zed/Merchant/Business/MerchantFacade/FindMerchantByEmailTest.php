<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use SprykerTest\Zed\Merchant\Business\AbstractMerchantFacadeTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group FindMerchantByEmailTest
 * Add your own group annotations below this line
 */
class FindMerchantByEmailTest extends AbstractMerchantFacadeTest
{
    protected const MERCHANT_EMAIL = 'merchant@test.test';

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $actualMerchant = $this->tester->getFacade()->findMerchantByEmail($expectedMerchant->getEmail());

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillNotFindMerchant(): void
    {
        $actualMerchant = $this->tester->getFacade()->findMerchantByEmail(static::MERCHANT_EMAIL);

        $this->assertNull($actualMerchant);
    }
}
