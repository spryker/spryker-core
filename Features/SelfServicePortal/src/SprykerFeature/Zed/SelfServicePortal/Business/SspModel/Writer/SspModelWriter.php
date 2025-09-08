<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspModelCollectionRequestTransfer;
use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Validator\SspModelValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspModelWriter implements SspModelWriterInterface
{
    use TransactionTrait;

    public function __construct(
        protected SelfServicePortalEntityManagerInterface $entityManager,
        protected SelfServicePortalRepositoryInterface $repository,
        protected SspModelValidatorInterface $sspModelValidator,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected SelfServicePortalConfig $config,
        protected FileSspModelWriterInterface $fileSspModelWriter
    ) {
    }

    public function createSspModelCollection(
        SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
    ): SspModelCollectionResponseTransfer {
        $sspModelCollectionResponseTransfer = new SspModelCollectionResponseTransfer();

        foreach ($sspModelCollectionRequestTransfer->getSspModels() as $sspModelTransfer) {
            if (!$this->sspModelValidator->validateModelTransfer($sspModelTransfer, $sspModelCollectionResponseTransfer)) {
                continue;
            }

            $sspModelTransfer = $this->executeModelCreation($sspModelTransfer);
            $sspModelCollectionResponseTransfer->addSspModel($sspModelTransfer);
        }

        return $sspModelCollectionResponseTransfer;
    }

    protected function executeModelCreation(SspModelTransfer $sspModelTransfer): SspModelTransfer
    {
        $sspModelTransfer
            ->setReference($this->sequenceNumberFacade->generate($this->config->getModelSequenceNumberSettings()));

        return $this->getTransactionHandler()->handleTransaction(function () use ($sspModelTransfer) {
            $sspModelTransfer = $this->fileSspModelWriter->createFile($sspModelTransfer);

            return $this->entityManager->createSspModel($sspModelTransfer);
        });
    }

    public function updateSspModelCollection(
        SspModelCollectionRequestTransfer $sspModelCollectionRequestTransfer
    ): SspModelCollectionResponseTransfer {
        $sspModelCollectionResponseTransfer = new SspModelCollectionResponseTransfer();

        foreach ($sspModelCollectionRequestTransfer->getSspModels() as $sspModelTransfer) {
            if (!$this->sspModelValidator->validateModelTransfer($sspModelTransfer, $sspModelCollectionResponseTransfer)) {
                continue;
            }

            $sspModelTransfer = $this->executeModelUpdate($sspModelTransfer, $sspModelCollectionResponseTransfer);
            if ($sspModelTransfer) {
                $sspModelCollectionResponseTransfer->addSspModel($sspModelTransfer);
            }
        }

        return $sspModelCollectionResponseTransfer;
    }

    protected function executeModelUpdate(
        SspModelTransfer $sspModelTransfer,
        SspModelCollectionResponseTransfer $sspModelCollectionResponseTransfer
    ): ?SspModelTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($sspModelTransfer, $sspModelCollectionResponseTransfer) {
            $sspModelTransfer = $this->fileSspModelWriter->updateFile($sspModelTransfer);

            $sspModelTransfer = $this->entityManager->updateSspModel($sspModelTransfer);
            if (!$sspModelTransfer) {
                $sspModelCollectionResponseTransfer->addError(
                    (new ErrorTransfer())->setMessage('SSP Model not found'),
                );
            }

            return $sspModelTransfer;
        });
    }
}
