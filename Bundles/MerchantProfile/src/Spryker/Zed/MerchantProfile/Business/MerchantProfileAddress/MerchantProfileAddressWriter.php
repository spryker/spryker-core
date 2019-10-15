<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface;

class MerchantProfileAddressWriter implements MerchantProfileAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface
     */
    protected $merchantProfileEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface $merchantProfileEntityManager
     */
    public function __construct(MerchantProfileEntityManagerInterface $merchantProfileEntityManager)
    {
        $this->merchantProfileEntityManager = $merchantProfileEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function create(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer
    {
        $this->assertCreateMerchantProfileAddressTransferRequirements($merchantProfileAddressTransfer);

        return $this->merchantProfileEntityManager->saveMerchantProfileAddress($merchantProfileAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function update(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer
    {
        $this->assertUpdateMerchantProfileAddressTransferRequirements($merchantProfileAddressTransfer);

        return $this->merchantProfileEntityManager->saveMerchantProfileAddress($merchantProfileAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
     * @param int $idMerchantProfile
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer
     */
    public function saveMerchantProfileAddressCollection(MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer, int $idMerchantProfile): MerchantProfileAddressCollectionTransfer
    {
        $savedMerchantProfileAddressCollectionTransfer = new MerchantProfileAddressCollectionTransfer();

        foreach ($merchantProfileAddressCollectionTransfer->getAddresses() as $merchantProfileAddressTransfer) {
            $merchantProfileAddressTransfer->setFkMerchantProfile($idMerchantProfile);
            $savedMerchantProfileAddressCollectionTransfer->addAddress($this->saveMerchantProfileAddress($merchantProfileAddressTransfer));
        }

        return $savedMerchantProfileAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    protected function saveMerchantProfileAddress(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer
    {
        if ($merchantProfileAddressTransfer->getIdMerchantProfileAddress() === null) {
            return $this->create($merchantProfileAddressTransfer);
        }

        return $this->update($merchantProfileAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return void
     */
    protected function assertCreateMerchantProfileAddressTransferRequirements(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): void
    {
        $merchantProfileAddressTransfer
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireFkMerchantProfile()
            ->requireFkCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return void
     */
    protected function assertUpdateMerchantProfileAddressTransferRequirements(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): void
    {
        $merchantProfileAddressTransfer
            ->requireIdMerchantProfileAddress()
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireFkMerchantProfile()
            ->requireFkCountry();
    }
}
