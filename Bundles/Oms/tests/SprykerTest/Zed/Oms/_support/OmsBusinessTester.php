<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms;

use Codeception\Actor;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class OmsBusinessTester extends Actor
{
    use _generated\OmsBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $testStateMachineProcessName
     * @param \Spryker\Zed\Checkout\Dependency\Plugin\CheckoutSaveOrderInterface[]|\Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface[] $saveOrderStack
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithShipment(
        QuoteTransfer $quoteTransfer,
        ?string $testStateMachineProcessName = 'Test01',
        array $saveOrderStack = []
    ): SaveOrderTransfer {
        $this->configureTestStateMachine([$testStateMachineProcessName]);

        return $this->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, $testStateMachineProcessName, $saveOrderStack);
    }
}
