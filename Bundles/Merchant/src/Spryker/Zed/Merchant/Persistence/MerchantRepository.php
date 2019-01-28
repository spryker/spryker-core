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
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantPersistenceFactory getFactory()
 */
class MerchantRepository extends AbstractRepository implements MerchantRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function getMerchantById(int $idMerchant): ?MerchantTransfer
    {
        $spyMerchant = $this->getFactory()
            ->createMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->useSpyMerchantAddressQuery(null, Criteria::LEFT_JOIN)
                ->limit(1)
            ->endUse()
            ->findOne();

        if (!$spyMerchant) {
            return null;
        }

        return $this->getFactory()
            ->createPropelMerchantMapper()
            ->mapEntityToMerchantTransfer($spyMerchant, new MerchantTransfer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
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
