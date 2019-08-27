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
 * @group GetNextStatusesTest
 * Add your own group annotations below this line
 */
class GetNextStatusesTest extends AbstractMerchantFacadeTest
{
    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnArray(): void
    {
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses($this->tester->createMerchantConfig()->getDefaultMerchantStatus());

        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertNotEmpty($applicableMerchantStatuses);
    }

    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnEmptyArrayOnNotFoundCurrentStatus(): void
    {
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses('random-status');

        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertEmpty($applicableMerchantStatuses);
    }
}
