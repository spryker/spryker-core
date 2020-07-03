<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage;

use Codeception\Actor;
use Orm\Zed\MerchantProductStorage\Persistence\SpyMerchantProductAbstractStorageQuery;

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
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductStorageTester extends Actor
{
    use _generated\MerchantProductStorageTesterActions;

    /**
     * @param $idAbstractProduct
     *
     * @return int
     */
    public function countMerchantProductAbstract($idAbstractProduct)
    {
        return SpyMerchantProductAbstractStorageQuery::create()->filterByFkProductAbstract($idAbstractProduct)->find()->count();
    }
}
