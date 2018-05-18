<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\SpyMerchantEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantEntityManager extends AbstractEntityManager implements MerchantEntityManagerInterface
{
    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function deleteMerchantById(int $idMerchant): void
    {
        $this->getFactory()->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyMerchantEntityTransfer $merchantEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyMerchantEntityTransfer
     */
    public function saveMerchant(SpyMerchantEntityTransfer $merchantEntityTransfer): SpyMerchantEntityTransfer
    {
        $merchantEntityTransfer = $this->save($merchantEntityTransfer);

        return (new SpyMerchantEntityTransfer())->fromArray($merchantEntityTransfer->toArray());
    }
}
