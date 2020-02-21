<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesReturn;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnBusinessTester extends Actor
{
    use _generated\SalesReturnBusinessTesterActions;

    /**
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function createCalculableObjectWithFakeRemuneration(): CalculableObjectTransfer
    {
        $itemTransfers = [
            (new ItemTransfer())
                ->setRemunerationAmount(100),
            (new ItemTransfer())
                ->setRemunerationAmount(200),
            (new ItemTransfer())
                ->setRemunerationAmount(300),
        ];

        return (new CalculableObjectTransfer())
            ->setItems(new ArrayObject($itemTransfers))
            ->setTotals(new TotalsTransfer());
    }
}
