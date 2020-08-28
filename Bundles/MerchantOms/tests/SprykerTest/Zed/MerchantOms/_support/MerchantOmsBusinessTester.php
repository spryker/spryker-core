<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms;

use Codeception\Actor;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOmsBusinessTester extends Actor
{
    use _generated\MerchantOmsBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param string $stateMachine
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer(MerchantTransfer $merchantTransfer, string $stateMachine): SaveOrderTransfer
    {
        $this->configureTestStateMachine([$stateMachine]);

        return $this->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], $stateMachine);
    }
}
