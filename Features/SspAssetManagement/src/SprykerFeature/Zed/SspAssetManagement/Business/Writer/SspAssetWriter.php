<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Business\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Validator\SspAssetValidatorInterface;
use SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementEntityManagerInterface;
use SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig;

class SspAssetWriter implements SspAssetWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const MESSAGE_ASSET_ID_NOT_PROVIDED = 'ssp_asset.validation.id_not_provided';

    /**
     * @param \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementEntityManagerInterface $entityManager
     * @param \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface $repository
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\Validator\SspAssetValidatorInterface $sspAssetValidator
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     * @param \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig $config
     * @param \SprykerFeature\Zed\SspAssetManagement\Business\Writer\FileSspAssetWriterInterface $fileSspAssetWriter
     */
    public function __construct(
        protected SspAssetManagementEntityManagerInterface $entityManager,
        protected SspAssetManagementRepositoryInterface $repository,
        protected SspAssetValidatorInterface $sspAssetValidator,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected SspAssetManagementConfig $config,
        protected FileSspAssetWriterInterface $fileSspAssetWriter
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function createSspAssetCollection(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        $sspAssetCollectionResponseTransfer = new SspAssetCollectionResponseTransfer();
        foreach ($sspAssetCollectionRequestTransfer->getSspAssets() as $sspAssetTransfer) {
            if (!$this->validateAsset($sspAssetTransfer, $sspAssetCollectionResponseTransfer)) {
                continue;
            }

            $sspAssetTransfer = $this->executeAssetCreation($sspAssetTransfer);
            $sspAssetCollectionResponseTransfer->addSspAsset($sspAssetTransfer);
        }

        return $sspAssetCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function updateSspAssetCollection(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        $sspAssetCollectionResponseTransfer = new SspAssetCollectionResponseTransfer();

        foreach ($sspAssetCollectionRequestTransfer->getSspAssets() as $sspAssetTransfer) {
            if (!$this->validateAssetForUpdate($sspAssetTransfer, $sspAssetCollectionResponseTransfer)) {
                continue;
            }

            $sspAssetTransfer = $this->executeAssetUpdate($sspAssetTransfer);
            $sspAssetCollectionResponseTransfer->addSspAsset($sspAssetTransfer);
        }

        $businessUnitToDeleteGroupedBySspAssetId = [];

        foreach ($sspAssetCollectionRequestTransfer->getAssignmentsToRemove() as $sspAssetAssignmentTransfer) {
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit() === $sspAssetAssignmentTransfer->getSspAssetOrFail()->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit()) {
                $sspAssetCollectionResponseTransfer->addError(
                    (new ErrorTransfer())->setMessage('ssp_asset.validation.cannot_remove_own_assignment'),
                );

                continue;
            }
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()) {
                $businessUnitToDeleteGroupedBySspAssetId[$sspAssetAssignmentTransfer->getSspAssetOrFail()->getIdSspAssetOrFail()][] = $sspAssetAssignmentTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnitOrFail();
            }
        }

        foreach ($businessUnitToDeleteGroupedBySspAssetId as $idSspAsset => $companyBusinessUnitIds) {
            $this->entityManager->deleteAssetToCompanyBusinessUnitRelations($idSspAsset, $companyBusinessUnitIds);
        }

        return $sspAssetCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return bool
     */
    protected function validateAsset(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): bool {
        $validationErrorTransfers = $this->sspAssetValidator->validateAsset($sspAssetTransfer);

        foreach ($validationErrorTransfers as $validationErrorTransfer) {
            $sspAssetCollectionResponseTransfer->addError($validationErrorTransfer);
        }

        return $sspAssetCollectionResponseTransfer->getErrors()->count() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return bool
     */
    protected function validateAssetForUpdate(
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): bool {
        if (!$sspAssetTransfer->getIdSspAsset()) {
            $sspAssetCollectionResponseTransfer->addError(
                (new ErrorTransfer())->setMessage(static::MESSAGE_ASSET_ID_NOT_PROVIDED),
            );

            return false;
        }

        return $this->validateAsset($sspAssetTransfer, $sspAssetCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function executeAssetCreation(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $sspAssetTransfer
            ->setReference($this->sequenceNumberFacade->generate($this->config->getAssetSequenceNumberSettings()))
            ->setStatus($this->config->getInitialAssetStatus());

        return $this->getTransactionHandler()->handleTransaction(function () use ($sspAssetTransfer) {
            $sspAssetTransfer = $this->fileSspAssetWriter->createFile($sspAssetTransfer);

            return $this->entityManager->createSspAsset($sspAssetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    protected function executeAssetUpdate(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($sspAssetTransfer) {
            $sspAssetTransfer = $this->fileSspAssetWriter->updateFile($sspAssetTransfer);

            return $this->entityManager->updateSspAsset($sspAssetTransfer);
        });
    }
}
