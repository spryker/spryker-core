<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileStorage;

use Codeception\Actor;

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
class MerchantProfileStorageCommunicationTester extends Actor
{
    use _generated\MerchantProfileStorageCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param int $idMerchantProfile
     *
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\Base\SpyMerchantProfileStorage|null
     */
    public function findMerchantProfileStorageByIdMerchantProfile(int $idMerchantProfile): ?SpyMerchantProfileStorage
    {
        return $this->getMerchantProfileStorageQuery()->findOneByFkMerchantProfile($idMerchantProfile);
    }

    /**
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorageQuery
     */
    protected function getMerchantProfileStorageQuery(): SpyMerchantProfileStorageQuery
    {
        return SpyMerchantProfileStorageQuery::create();
    }
}
