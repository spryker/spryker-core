<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
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
    public function findMerchantById(int $idMerchant): ?MerchantTransfer
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
            ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
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
            ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function getMerchants(): MerchantCollectionTransfer
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
                $mapper->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer())
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
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function getMerchantAddresses(int $idMerchant): MerchantAddressCollectionTransfer
    {
        $spyMerchantAddresses = $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByFkMerchant($idMerchant)
            ->find();

        $merchantAddressMapper = $this->getFactory()->createPropelMerchantAddressMapper();

        $merchantAddressCollectionTransfer = new MerchantAddressCollectionTransfer();
        foreach ($spyMerchantAddresses as $spyMerchantAddress) {
            $merchantAddressCollectionTransfer->addAddress(
                $merchantAddressMapper->mapSpyMerchantAddressEntityToMerchantAddressTransfer(
                    $spyMerchantAddress,
                    new MerchantAddressTransfer()
                )
            );
        }

        return $merchantAddressCollectionTransfer;
    }

    /**
     * @param int $idMerchantAddress
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer|null
     */
    public function findMerchantAddressById(int $idMerchantAddress): ?MerchantAddressTransfer
    {
        $spyMerchantAddress = $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByIdMerchantAddress($idMerchantAddress)
            ->findOne();

        if (!$spyMerchantAddress) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantAddressMapper()
            ->mapSpyMerchantAddressEntityToMerchantAddressTransfer($spyMerchantAddress, new MerchantAddressTransfer());
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasAddressKey(string $key): bool
    {
        return $this->getFactory()
            ->createMerchantAddressQuery()
            ->filterByKey($key)
            ->exists();
    }
}
