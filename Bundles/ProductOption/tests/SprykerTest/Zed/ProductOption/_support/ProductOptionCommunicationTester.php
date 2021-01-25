<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use Codeception\Actor;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionCommunicationTester extends Actor
{
    use _generated\ProductOptionCommunicationTesterActions;

    /**
     * @param string $sku
     * @param int $idProductOptionGroup
     *
     * @return void
     */
    public function createProductOptionValueEntity(string $sku, int $idProductOptionGroup): void
    {
        $productOptionValue = new SpyProductOptionValue();
        $productOptionValue->setSku($sku);
        $productOptionValue->setValue($sku);
        $productOptionValue->setFkProductOptionGroup($idProductOptionGroup);

        $productOptionValue->save();
    }
}
