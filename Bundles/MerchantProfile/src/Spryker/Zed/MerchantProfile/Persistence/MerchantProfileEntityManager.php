<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile;
use Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantProfile\Persistence\MerchantProfilePersistenceFactory getFactory()
 */
class MerchantProfileEntityManager extends AbstractEntityManager implements MerchantProfileEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function create(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->saveMerchantProfile($merchantProfileTransfer, new SpyMerchantProfile());

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function update(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->getFactory()
            ->createMerchantProfileQuery()
            ->filterByIdMerchantProfile($merchantProfileTransfer->getIdMerchantProfile())
            ->findOne();

        $merchantProfileTransfer = $this->saveMerchantProfile($merchantProfileTransfer, $merchantProfileEntity);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfile $merchantProfileEntity
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer, SpyMerchantProfile $merchantProfileEntity): MerchantProfileTransfer
    {
        $merchantProfileEntity = $this->getFactory()
            ->createPropelMerchantProfileMapper()
            ->mapMerchantProfileTransferToMerchantProfileEntity($merchantProfileTransfer, $merchantProfileEntity);

        $merchantProfileEntity->save();

        $merchantProfileTransfer->setIdMerchantProfile($merchantProfileEntity->getIdMerchantProfile());

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function createMerchantProfileAddress(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer
    {
        return $this->saveMerchantProfileAddress($merchantProfileAddressTransfer, new SpyMerchantProfileAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function updateMerchantProfileAddress(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer
    {
        $merchantProfileAddressEntity = $this->getFactory()
            ->createMerchantProfileAddressQuery()
            ->filterByIdMerchantProfileAddress($merchantProfileAddressTransfer->getIdMerchantProfileAddress())
            ->findOneOrCreate();

        return $this->saveMerchantProfileAddress($merchantProfileAddressTransfer, $merchantProfileAddressEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     * @param \Orm\Zed\MerchantProfile\Persistence\SpyMerchantProfileAddress $merchantProfileAddressEntity
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    protected function saveMerchantProfileAddress(
        MerchantProfileAddressTransfer $merchantProfileAddressTransfer,
        SpyMerchantProfileAddress $merchantProfileAddressEntity
    ): MerchantProfileAddressTransfer {
        $merchantProfileAddressEntity = $this->getFactory()
            ->createMerchantProfileAddressMapper()
            ->mapMerchantProfileAddressTransferToMerchantProfileAddressEntity($merchantProfileAddressTransfer, $merchantProfileAddressEntity);

        $merchantProfileAddressEntity->save();

        $merchantProfileAddressTransfer = $this->getFactory()
            ->createMerchantProfileAddressMapper()
            ->mapMerchantProfileAddressEntityToMerchantProfileAddressTransfer($merchantProfileAddressEntity, $merchantProfileAddressTransfer);

        return $merchantProfileAddressTransfer;
    }
}
