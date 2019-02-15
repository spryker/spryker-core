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
    protected $merchantEntityManager;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     */
    public function __construct(MerchantEntityManagerInterface $merchantEntityManager)
    {
        $this->merchantEntityManager = $merchantEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function create(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $this->assertCreateMerchantAddressTransferRequirements($merchantAddressTransfer);

        return $this->merchantEntityManager->saveMerchantAddress($merchantAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function update(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer
    {
        $this->assertUpdateMerchantAddressTransferRequirements($merchantAddressTransfer);

        return $this->merchantEntityManager->saveMerchantAddress($merchantAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return void
     */
    protected function assertCreateMerchantAddressTransferRequirements(MerchantAddressTransfer $merchantAddressTransfer): void
    {
        $merchantAddressTransfer
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireFkMerchant()
            ->requireFkCountry();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return void
     */
    protected function assertUpdateMerchantAddressTransferRequirements(MerchantAddressTransfer $merchantAddressTransfer): void
    {
        $merchantAddressTransfer
            ->requireIdMerchantAddress()
            ->requireCity()
            ->requireZipCode()
            ->requireAddress1()
            ->requireFkMerchant()
            ->requireFkCountry();
    }
}
