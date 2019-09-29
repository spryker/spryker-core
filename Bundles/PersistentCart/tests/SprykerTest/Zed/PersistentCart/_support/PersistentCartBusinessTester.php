<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCart;

use Codeception\Actor;

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
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PersistentCartBusinessTester extends Actor
{
    use _generated\PersistentCartBusinessTesterActions;

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function haveProducts(array $skus): array
    {
        $productTransfers = [];
        $skus = array_unique($skus);

        foreach ($skus as $sku) {
            $productTransfers[] = $this->haveProduct(['sku' => $sku]);
        }

        return $productTransfers;
    }
}
