<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspAssetWriter implements SspAssetWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const MESSAGE_ASSET_ID_NOT_PROVIDED = 'self_service_portal.asset.validation.id_not_provided';

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $entityManager
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $repository
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidatorInterface $sspAssetValidator
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $config
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriterInterface $fileSspAssetWriter
     */
    public function __construct(
        protected SelfServicePortalEntityManagerInterface $entityManager,
        protected SelfServicePortalRepositoryInterface $repository,
        protected SspAssetValidatorInterface $sspAssetValidator,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected SelfServicePortalConfig $config,
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

        $this->deleteBusinessUnitAssignments($sspAssetCollectionRequestTransfer, $sspAssetCollectionResponseTransfer);
        $this->createBusinessUnitAssignments($sspAssetCollectionRequestTransfer, $sspAssetCollectionResponseTransfer);

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
            ->setStatus($sspAssetTransfer->getStatus() ?: $this->config->getInitialAssetStatus());

        return $this->getTransactionHandler()->handleTransaction(function () use ($sspAssetTransfer) {
            $sspAssetTransfer = $this->fileSspAssetWriter->createFile($sspAssetTransfer);

            $sspAssetTransfer = $this->entityManager->createSspAsset($sspAssetTransfer);

            if (!$sspAssetTransfer->getBusinessUnitAssignments()->count()) {
                return $sspAssetTransfer;
            }

            $companyBusinessUnitIds = [];
            foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $sspAssetAssignmentTransfer) {
                if (!$sspAssetAssignmentTransfer->getCompanyBusinessUnit()) {
                    continue;
                }

                $companyBusinessUnitIds[] = $sspAssetAssignmentTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnitOrFail();
            }

            $this->entityManager->createAssetToCompanyBusinessUnitRelation(
                $sspAssetTransfer->getIdSspAssetOrFail(),
                $companyBusinessUnitIds,
            );

            return $sspAssetTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    protected function deleteBusinessUnitAssignments(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): SspAssetCollectionResponseTransfer {
        $businessUnitToDeleteGroupedBySspAssetId = [];
        foreach ($sspAssetCollectionRequestTransfer->getBusinessUnitAssignmentsToDelete() as $sspAssetAssignmentTransfer) {
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit() === $sspAssetAssignmentTransfer->getSspAssetOrFail()->getCompanyBusinessUnit()?->getIdCompanyBusinessUnit()) {
                $sspAssetCollectionResponseTransfer->addError(
                    (new ErrorTransfer())->setMessage('ssp_asset.validation.cannot_delete_own_assignment'),
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
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    protected function createBusinessUnitAssignments(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer,
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer
    ): SspAssetCollectionResponseTransfer {
        $businessUnitToAddGroupedBySspAssetId = [];
        foreach ($sspAssetCollectionRequestTransfer->getBusinessUnitAssignmentsToAdd() as $sspAssetAssignmentTransfer) {
            if ($sspAssetAssignmentTransfer->getCompanyBusinessUnit()) {
                $businessUnitToAddGroupedBySspAssetId[$sspAssetAssignmentTransfer->getSspAssetOrFail()->getIdSspAssetOrFail()][] = $sspAssetAssignmentTransfer->getCompanyBusinessUnit()->getIdCompanyBusinessUnitOrFail();
            }
        }

        foreach ($businessUnitToAddGroupedBySspAssetId as $idSspAsset => $companyBusinessUnitIds) {
            $this->entityManager->createAssetToCompanyBusinessUnitRelation($idSspAsset, $companyBusinessUnitIds);
        }

        return $sspAssetCollectionResponseTransfer;
    }
}
