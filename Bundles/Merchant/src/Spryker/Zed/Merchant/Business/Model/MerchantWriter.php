<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface;
use Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface;
use Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\MerchantConfig;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;

class MerchantWriter implements MerchantWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $merchantEntityManager;

    /**
     * @var \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface
     *
     */
    protected $merchantKeyGenerator;

    /**
     * @var \Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface
     */
    protected $merchantAddressWriter;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface
     */
    protected $merchantStatusValidator;

    /**
     * @var \Spryker\Zed\Merchant\MerchantConfig
     */
    protected $merchantConfig;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\Business\KeyGenerator\MerchantKeyGeneratorInterface $merchantKeyGenerator
     * @param \Spryker\Zed\Merchant\Business\MerchantAddress\MerchantAddressWriterInterface $merchantAddressWriter
     * @param \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface $merchantStatusValidator
     * @param \Spryker\Zed\Merchant\MerchantConfig $merchantConfig
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantKeyGeneratorInterface $merchantKeyGenerator,
        MerchantAddressWriterInterface $merchantAddressWriter,
        MerchantStatusValidatorInterface $merchantStatusValidator,
        MerchantConfig $merchantConfig
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantKeyGenerator = $merchantKeyGenerator;
        $this->merchantAddressWriter = $merchantAddressWriter;
        $this->merchantStatusValidator = $merchantStatusValidator;
        $this->merchantConfig = $merchantConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function create(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $this->assertDefaultMerchantRequirements($merchantTransfer);

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer->setStatus($this->merchantConfig->getDefaultMerchantStatus());

        $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            return $this->executeCreateTransaction($merchantTransfer);
        });

        $merchantResponseTransfer = $this->createMerchantResponseTransfer();
        $merchantResponseTransfer = $merchantResponseTransfer
            ->setIsSuccess(true)
            ->setMerchant($merchantTransfer);

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    public function update(MerchantTransfer $merchantTransfer): MerchantResponseTransfer
    {
        $this->assertDefaultMerchantRequirements($merchantTransfer);
        $merchantTransfer->requireIdMerchant();

        $merchantResponseTransfer = $this->createMerchantResponseTransfer();

        if (!$this->isMerchantStatusTransitionValid($merchantTransfer->getIdMerchant(), $merchantTransfer->getStatus())) {
            return $merchantResponseTransfer;
        }

        if (empty($merchantTransfer->getMerchantKey())) {
            $merchantTransfer->setMerchantKey(
                $this->merchantKeyGenerator->generateMerchantKey($merchantTransfer->getName())
            );
        }

        $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
            return $this->executeUpdateTransaction($merchantTransfer);
        });

        $merchantResponseTransfer = $merchantResponseTransfer
            ->setIsSuccess(true)
            ->setMerchant($merchantTransfer);

        return $merchantResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeCreateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantAddressCollectionTransfer = $merchantTransfer->getAddressCollection();
        $merchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        $merchantAddressCollectionTransfer = $this->handleMerchantAddressCollectionSave($merchantAddressCollectionTransfer, $merchantTransfer->getIdMerchant());
        $merchantTransfer->setAddressCollection($merchantAddressCollectionTransfer);

        return $merchantTransfer;
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
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeUpdateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantAddressCollectionTransfer = $this->handleMerchantAddressCollectionSave($merchantTransfer->getAddressCollection(), $merchantTransfer->getIdMerchant());
        $merchantTransfer->setAddressCollection($merchantAddressCollectionTransfer);

        return $this->merchantEntityManager->saveMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    protected function handleMerchantAddressCollectionSave(MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer, int $idMerchant): MerchantAddressCollectionTransfer
    {
        $savedMerchantAddressCollectionTransfer = new MerchantAddressCollectionTransfer();

        foreach ($merchantAddressCollectionTransfer->getAddresses() as $merchantAddressTransfer) {
            $savedMerchantAddressCollectionTransfer->addAddress($this->handleMerchantAddressSave($merchantAddressTransfer, $idMerchant));
        }

        return $savedMerchantAddressCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    protected function handleMerchantAddressSave(MerchantAddressTransfer $merchantAddressTransfer, int $idMerchant): MerchantAddressTransfer
    {
        $merchantAddressTransfer->setFkMerchant($idMerchant);

        if ($merchantAddressTransfer->getIdMerchantAddress() === null) {
            return $this->merchantAddressWriter->create($merchantAddressTransfer);
        }

        return $this->merchantAddressWriter->update($merchantAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function executeDeleteTransaction(MerchantTransfer $merchantTransfer): void
    {
        $idMerchant = $merchantTransfer->getIdMerchant();

        $this->merchantEntityManager->deleteMerchantAddressesByIdMerchant($idMerchant);
        $this->merchantEntityManager->deleteMerchantByIdMerchant($idMerchant);
    }

    /**
     * @param int $idMerchant
     * @param string $newStatus
     *
     * @return bool
     */
    protected function isMerchantStatusTransitionValid(int $idMerchant, string $newStatus): bool
    {
        return $this->merchantStatusValidator->isMerchantStatusTransitionValid($idMerchant, $newStatus);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return void
     */
    protected function assertDefaultMerchantRequirements(MerchantTransfer $merchantTransfer): void
    {
        $merchantTransfer
            ->requireName()
            ->requireRegistrationNumber()
            ->requireContactPersonTitle()
            ->requireContactPersonFirstName()
            ->requireContactPersonLastName()
            ->requireContactPersonPhone()
            ->requireEmail()
            ->requireAddressCollection();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(): MerchantResponseTransfer
    {
        return (new MerchantResponseTransfer())
            ->setIsSuccess(false);
    }
}
