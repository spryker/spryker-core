<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\MerchantAddress;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantAddressWriter implements MerchantAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     */
    public function __construct(MerchantEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function create(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $merchantAddressTransfer
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireAddress2()
            ->requireFkMerchant()
            ->requireFkCountry();

        return $this->entityManager->saveMerchantAddress($merchantAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function update(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $merchantAddressTransfer
            ->requireIdMerchantAddress()
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireAddress2()
            ->requireFkMerchant()
            ->requireFkCountry();

        return $this->entityManager->saveMerchantAddress($merchantAddressTransfer);
    }
}
