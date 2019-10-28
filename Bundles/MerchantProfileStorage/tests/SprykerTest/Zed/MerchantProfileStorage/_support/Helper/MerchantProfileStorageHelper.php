<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfileStorage\Helper;

use Codeception\Module;
use Orm\Zed\MerchantProfileStorage\Persistence\Base\SpyMerchantProfileStorage;
use Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorageQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantProfileStorageHelper extends Module
{
    use LocatorHelperTrait;

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
