<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantRepository extends AbstractRepository implements MerchantRepositoryInterface
{
    /**
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByIdMerchant(int $idMerchant): ?MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->findOne();

        if (!$spyMerchant) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }

    /**
     * @param string $merchantEmail
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchantByEmail(string $merchantEmail): ?MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByEmail($merchantEmail)
            ->findOne();

        if (!$spyMerchant) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapMerchantEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchantCollection(): MerchantCollectionTransfer
    {
        $spyMerchants = $this->getFactory()
            ->createMerchantQuery()
            ->orderByName()
            ->find();

        $mapper = $this->getFactory()
            ->createPropelMerchantMapper();

        $merchantCollectionTransfer = new MerchantCollectionTransfer();
        foreach ($spyMerchants as $spyMerchant) {
            $merchantCollectionTransfer->addMerchants(
                $mapper->mapMerchantEntityToMerchantTransfer($spyMerchant, new MerchantTransfer())
            );
        }

        return $merchantCollectionTransfer;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getFactory()
            ->createMerchantQuery()
            ->filterByMerchantKey($key)
            ->exists();
    }

    /**
     * @param int $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressByIdMerchantAddress(int $idMerchantAddress): ?MerchantAddressTransfer
    {
        $spyMerchantAddress = $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByIdMerchantAddress($idMerchantAddress)
            ->findOne();

        if (!$spyMerchantAddress) {
            return null;
        }

        return $this->getFactory()
            ->createMerchantAddressMapper()
            ->mapMerchantAddressEntityToMerchantAddressTransfer($spyMerchantAddress, new MerchantAddressTransfer());
    }
}
