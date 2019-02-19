<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
    public function deleteMerchantByIdMerchant(int $idMerchant): void
    {
        $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->delete();
    }

    /**
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
            ->mapMerchantTransferToMerchantEntity($merchantTransfer, $spyMerchant);

        $spyMerchant->save();

        $merchantTransfer = $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($spyMerchant, $merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function saveMerchantAddress(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $spyMerchantAddress = $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByIdMerchantAddress($merchantAddressTransfer->getIdMerchantAddress())
            ->findOneOrCreate();

        $spyMerchantAddress = $this->getFactory()
            ->createMerchantAddressMapper()
            ->mapMerchantAddressTransferToSpyMerchantAddressEntity($merchantAddressTransfer, $spyMerchantAddress);

        $spyMerchantAddress->save();

        $merchantAddressTransfer = $this->getFactory()
            ->createMerchantAddressMapper()
            ->mapMerchantAddressEntityToMerchantAddressTransfer($spyMerchantAddress, $merchantAddressTransfer);

        return $merchantAddressTransfer;
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function deleteMerchantAddressesByIdMerchant(int $idMerchant): void
    {
        $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByFkMerchant($idMerchant)
            ->delete();
    }
}
