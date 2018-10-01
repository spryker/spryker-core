<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function haveMerchant(array $seedData = []): MerchantTransfer
    {
        $merchantTransfer = (new MerchantBuilder($seedData))->build();
        $merchantTransfer->setIdMerchant(null);

        return $this->getLocator()->merchant()->facade()->createMerchant($merchantTransfer);
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function assertMerchantNotExists(int $idMerchant): void
    {
        $query = $this->getMerchantQuery()->filterByIdMerchant($idMerchant);
        $this->assertSame(0, $query->count());
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}
