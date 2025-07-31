<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Business\Saver;

use Exception;
use Generated\Shared\Transfer\MerchantFileImportResponseTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig;
use Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class MerchantFileImportSaver implements MerchantFileImportSaverInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const GENERIC_ERROR_MESSAGE = 'An error occurred while saving the merchant file import.';

    /**
     * @param \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig $config
     * @param \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface $entityManager
     */
    public function __construct(
        protected FileImportMerchantPortalGuiConfig $config,
        protected FileImportMerchantPortalGuiEntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportResponseTransfer
     */
    public function saveMerchantFileImport(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportResponseTransfer {
        $merchantFileImportResponseTransfer = (new MerchantFileImportResponseTransfer())
            ->setIsSuccessful(true);

        try {
            $merchantFileImportTransfer = $this->getTransactionHandler()->handleTransaction(
                fn () => $this->executeSaveMerchantFileImportTransaction($merchantFileImportTransfer),
            );

            $merchantFileImportResponseTransfer->setMerchantFileImport($merchantFileImportTransfer);
        } catch (Exception) {
            $merchantFileImportResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage(
                    (new MessageTransfer())->setMessage(static::GENERIC_ERROR_MESSAGE),
                );
        }

        return $merchantFileImportResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    protected function executeSaveMerchantFileImportTransaction(
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer {
        return $this->entityManager->saveMerchantFileImport($merchantFileImportTransfer);
    }
}
