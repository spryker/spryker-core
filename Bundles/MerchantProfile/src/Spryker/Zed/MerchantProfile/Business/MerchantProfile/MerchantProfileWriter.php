<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface;

class MerchantProfileWriter implements MerchantProfileWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface
     */
    protected $merchantProfileEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface
     */
    protected $merchantProfileGlossaryWriter;

    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface
     */
    protected $merchantProfileAddressWriter;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface $merchantProfileEntityManager
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface $merchantProfileAddressWriter
     */
    public function __construct(
        MerchantProfileEntityManagerInterface $merchantProfileEntityManager,
        MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter,
        MerchantProfileAddressWriterInterface $merchantProfileAddressWriter
    ) {
        $this->merchantProfileEntityManager = $merchantProfileEntityManager;
        $this->merchantProfileGlossaryWriter = $merchantProfileGlossaryWriter;
        $this->merchantProfileAddressWriter = $merchantProfileAddressWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function create(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantProfileTransfer) {
            return $this->executeCreateTransaction($merchantProfileTransfer);
        });

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function update(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantProfileTransfer) {
            return $this->executeUpdateTransaction($merchantProfileTransfer);
        });

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function executeCreateTransaction(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->merchantProfileGlossaryWriter->saveMerchantProfileGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->merchantProfileEntityManager->create($merchantProfileTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileAddress($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function executeUpdateTransaction(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->merchantProfileGlossaryWriter->saveMerchantProfileGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->merchantProfileEntityManager->update($merchantProfileTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileAddress($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileAddress(
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantProfileTransfer {
        if ($merchantProfileTransfer->getAddressCollection()->count() === 0) {
            return $merchantProfileTransfer;
        }

        $merchantProfileAddressTransfers = $this->merchantProfileAddressWriter->saveMerchantProfileAddresses(
            $merchantProfileTransfer->getAddressCollection(),
            $merchantProfileTransfer->getIdMerchantProfile()
        );
        $merchantProfileTransfer->setAddressCollection($merchantProfileAddressTransfers);

        return $merchantProfileTransfer;
    }
}
