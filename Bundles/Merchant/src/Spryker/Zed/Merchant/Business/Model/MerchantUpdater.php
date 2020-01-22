<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantErrorTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException;
use Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface;
use Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface;

class MerchantUpdater implements MerchantUpdaterInterface
{
    use TransactionTrait;

    protected const ERROR_MESSAGE_MERCHANT_NOT_FOUND = 'Merchant is not found.';
    protected const ERROR_MESSAGE_MERCHANT_STATUS_TRANSITION_NOT_VALID = 'Merchant status transition is not valid.';

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface
     */
    protected $merchantEntityManager;

    /**
     * @var \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface
     */
    protected $merchantRepository;

    /**
     * @var \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface
     */
    protected $merchantStatusValidator;

    /**
     * @var \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[]
     */
    protected $merchantPostUpdatePlugins;

    /**
     * @var array|\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[]
     */
    protected $merchantPostSavePlugins;

    /**
     * @param \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface $merchantEntityManager
     * @param \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface $merchantRepository
     * @param \Spryker\Zed\Merchant\Business\Model\Status\MerchantStatusValidatorInterface $merchantStatusValidator
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[] $merchantPostSavePlugins
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[] $merchantPostUpdatePlugins
     */
    public function __construct(
        MerchantEntityManagerInterface $merchantEntityManager,
        MerchantRepositoryInterface $merchantRepository,
        MerchantStatusValidatorInterface $merchantStatusValidator,
        array $merchantPostSavePlugins,
        array $merchantPostUpdatePlugins
    ) {
        $this->merchantEntityManager = $merchantEntityManager;
        $this->merchantRepository = $merchantRepository;
        $this->merchantStatusValidator = $merchantStatusValidator;
        $this->merchantPostUpdatePlugins = $merchantPostUpdatePlugins;
        $this->merchantPostSavePlugins = $merchantPostSavePlugins;
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

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setIdMerchant($merchantTransfer->getIdMerchant());

        $existingMerchantTransfer = $this->merchantRepository->findOne($merchantCriteriaFilterTransfer);
        if ($existingMerchantTransfer === null) {
            $merchantResponseTransfer = $this->addMerchantError($merchantResponseTransfer, static::ERROR_MESSAGE_MERCHANT_NOT_FOUND);

            return $merchantResponseTransfer;
        }

        if (!$this->merchantStatusValidator->isMerchantStatusTransitionValid($existingMerchantTransfer->getStatus(), $merchantTransfer->getStatus())) {
            $merchantResponseTransfer = $this->addMerchantError($merchantResponseTransfer, static::ERROR_MESSAGE_MERCHANT_STATUS_TRANSITION_NOT_VALID);

            return $merchantResponseTransfer;
        }

        try {
            $merchantTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($merchantTransfer) {
                return $this->executeUpdateTransaction($merchantTransfer);
            });
        } catch (MerchantNotSavedException $merchantNotSavedException) {
            return $merchantResponseTransfer
                ->setIsSuccess(false)
                ->setErrors($merchantNotSavedException->getErrors())
                ->setMerchant($merchantTransfer);
        }

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
    protected function executeUpdateTransaction(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $originalMerchantTransfer = $this->merchantRepository->findOne((new MerchantCriteriaFilterTransfer())->setIdMerchant($merchantTransfer->getIdMerchant()));
        $updatedMerchantTransfer = $this->merchantEntityManager->saveMerchant($merchantTransfer);
        $updatedMerchantTransfer = $this->executeMerchantPostUpdatePlugins($originalMerchantTransfer, $updatedMerchantTransfer);

        return $updatedMerchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $originalMerchantTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $updatedMerchantTransfer
     *
     * @throws \Spryker\Zed\Merchant\Business\Exception\MerchantNotSavedException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function executeMerchantPostUpdatePlugins(MerchantTransfer $originalMerchantTransfer, MerchantTransfer $updatedMerchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantPostSavePlugins as $merchantPostSavePlugin) {
            $merchantPostSavePlugin->execute($updatedMerchantTransfer);
        }

        foreach ($this->merchantPostUpdatePlugins as $merchantPostUpdatePlugin) {
            $merchantResponseTransfer = $merchantPostUpdatePlugin->postUpdate($originalMerchantTransfer, $updatedMerchantTransfer);
            if (!$merchantResponseTransfer->getIsSuccess()) {
                throw (new MerchantNotSavedException())->addErrors($merchantResponseTransfer->getErrors());
            }
        }

        return $updatedMerchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function createMerchantResponseTransfer(): MerchantResponseTransfer
    {
        return (new MerchantResponseTransfer())
            ->setIsSuccess(false);
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
            ->requireEmail();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantResponseTransfer $merchantResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function addMerchantError(MerchantResponseTransfer $merchantResponseTransfer, string $message): MerchantResponseTransfer
    {
        $merchantResponseTransfer->addError((new MerchantErrorTransfer())->setMessage($message));

        return $merchantResponseTransfer;
    }
}
