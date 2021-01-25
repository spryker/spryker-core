<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProductStorage;

use Codeception\Actor;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage;
use Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorageQuery;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantProductStorageTester extends Actor
{
    use _generated\MerchantProductStorageTesterActions;

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductStorage\Persistence\SpyProductAbstractStorage
     */
    public function getAbstractProductStorageByIdProductAbstract(int $idProductAbstract): SpyProductAbstractStorage
    {
        return SpyProductAbstractStorageQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->findOne();
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantFacadeInterface
    {
        return $this->getLocator()->merchant()->facade();
    }
}
