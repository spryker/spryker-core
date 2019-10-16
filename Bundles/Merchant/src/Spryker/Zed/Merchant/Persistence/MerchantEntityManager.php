<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantEntityManager extends AbstractEntityManager implements MerchantEntityManagerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param int $idMerchant
     *
     * @return void
     */
    public function deleteMerchantById(int $idMerchant): void
    {
        $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function saveMerchant(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($merchantTransfer->getIdMerchant())
            ->findOneOrCreate();

        $spyMerchant = $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantTransferToEntity($merchantTransfer, $spyMerchant);

        $spyMerchant->save();

        $merchantTransfer->setIdMerchant($spyMerchant->getIdMerchant());

        return $merchantTransfer;
    }
}
