<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantWriter implements MerchantWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface
     *
     */
    protected $merchantKeyGenerator;

    /**
     * @var \Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface
     */
    protected $merchantAddressWriter;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface $merchantKeyGenerator
     * @param \Spryker\Zed\Merchant\Business\Address\MerchantAddressWriterInterface $merchantAddressWriter
     * @param \Spryker\Zed\Merchant\MerchantConfig $config
     */
    public function __construct(
        MerchantEntityManagerInterface $entityManager,
        MerchantKeyGeneratorInterface $merchantKeyGenerator,
        MerchantAddressWriterInterface $merchantAddressWriter,
        MerchantConfig $config
    ) {
        $this->entityManager = $entityManager;
        $this->merchantKeyGenerator = $merchantKeyGenerator;
        $this->merchantAddressWriter = $merchantAddressWriter;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer
            ->requireName()
            ->requireRegistrationNumber()
            ->requireContactPersonTitle()
            ->requireContactPersonFirstName()
            ->requireContactPersonLastName()
            ->requireContactPersonPhone()
            ->requireEmail()
            ->requireAddress();

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer->setStatus($this->config->getDefaultMerchantStatus());

        $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            return $this->executeCreateTransaction($merchantTransfer);
        });

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeCreateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer = $this->entityManager->saveMerchant($merchantTransfer);

        $merchantAddressTransfer = $merchantTransfer->getAddress();
        $merchantAddressTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $merchantAddressTransfer = $this->merchantAddressWriter->create($merchantAddressTransfer);
        $merchantTransfer->setAddress($merchantAddressTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function update(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantTransfer
            ->requireIdMerchant()
            ->requireName()
            ->requireRegistrationNumber()
            ->requireContactPersonTitle()
            ->requireContactPersonFirstName()
            ->requireContactPersonLastName()
            ->requireContactPersonPhone()
            ->requireEmail()
            ->requireAddress();

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            return $this->executeUpdateTransaction($merchantTransfer);
        });

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeUpdateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantAddressTransfer = $merchantTransfer->getAddress();
        $merchantAddressTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        $merchantAddressTransfer = $this->merchantAddressWriter->update($merchantAddressTransfer);
        $merchantTransfer->setAddress($merchantAddressTransfer);

        return $this->entityManager->saveMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    public function delete(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer->requireIdMerchant();

        $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            $this->executeDeleteTransaction($merchantTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function executeDeleteTransaction(MerchantTransfer $merchantTransfer): void
    {
        $this->entityManager->deleteMerchantById($merchantTransfer->getIdMerchant());
    }
}
