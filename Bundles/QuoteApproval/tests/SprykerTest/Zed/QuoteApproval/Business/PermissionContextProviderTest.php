<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProvider;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Business
 * @group PermissionContextProviderTest
 * Add your own group annotations below this line
 */
class PermissionContextProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideContextShouldReturnGrandTotalInCentAmountArrayElement(): void
    {
        //Assign
        $quoteTransfer = (new QuoteTransfer())->setTotals(
            (new TotalsTransfer())->setGrandTotal(12345)
        );
        $permissionContextProvider = new PermissionContextProvider();

        //Act
        $context = $permissionContextProvider->provideContext($quoteTransfer);

        //Assert
        $this->assertArrayHasKey(QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT, $context);
        $this->assertEquals(12345, $context[QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT]);
    }
}
