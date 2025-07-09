<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyCollectionTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\SspAssetAttachmentForm;

class FileAttachmentMapper implements FileAttachmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param array<string, mixed> $formData
     * @param int $idFile
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer
     */
    public function mapFormDataToFileAttachmentCollectionTransfer(
        FileAttachmentTransfer $fileAttachmentTransfer,
        array $formData,
        int $idFile
    ): FileAttachmentCollectionRequestTransfer {
        $fileAttachmentToAdd = (new FileAttachmentTransfer())
            ->setFile((new FileTransfer())->setIdFile($idFile));
        $fileAttachmentToDelete = (new FileAttachmentTransfer())
            ->setFile((new FileTransfer())->setIdFile($idFile));

        $this->setCompanyUserFileAttachments($fileAttachmentTransfer, $formData, $fileAttachmentToAdd, $fileAttachmentToDelete);
        $this->setBusinessUnitFileAttachments($fileAttachmentTransfer, $formData, $fileAttachmentToAdd, $fileAttachmentToDelete);
        $this->setCompanyFileAttachments($fileAttachmentTransfer, $formData, $fileAttachmentToAdd, $fileAttachmentToDelete);
        $this->setSspAssetFileAttachments($fileAttachmentTransfer, $formData, $fileAttachmentToAdd, $fileAttachmentToDelete);

        return (new FileAttachmentCollectionRequestTransfer())
            ->addFileAttachmentToAdd($fileAttachmentToAdd)
            ->addFileAttachmentToDelete($fileAttachmentToDelete);
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setBusinessUnitFileAttachments(
        FileAttachmentTransfer $fileAttachmentTransfer,
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (!isset($formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS])) {
            return;
        }

        $existingFileAttachmentBusinessUnitIds = [];
        foreach ($fileAttachmentTransfer->getBusinessUnitCollectionOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $existingFileAttachmentBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        $businessUnitAttachmentToAdd = array_diff(
            $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS],
            $existingFileAttachmentBusinessUnitIds,
        );

        $fileAttachmentToAdd->setBusinessUnitCollection(new CompanyBusinessUnitCollectionTransfer());
        foreach ($businessUnitAttachmentToAdd as $idCompanyBusinessUnit) {
            $fileAttachmentToAdd->getBusinessUnitCollectionOrFail()->addCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit),
            );
        }

        $businessUnitAttachmentToDelete = array_diff(
            $existingFileAttachmentBusinessUnitIds,
            $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS],
        );

        $fileAttachmentToDelete->setBusinessUnitCollection(new CompanyBusinessUnitCollectionTransfer());

        foreach ($businessUnitAttachmentToDelete as $idCompanyBusinessUnit) {
            $fileAttachmentToDelete->getBusinessUnitCollectionOrFail()->addCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setCompanyFileAttachments(
        FileAttachmentTransfer $fileAttachmentTransfer,
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (!isset($formData[AttachFileForm::FIELD_COMPANY_IDS])) {
            return;
        }
        $existingFileAttachmentCompanyIds = [];
        foreach ($fileAttachmentTransfer->getCompanyCollectionOrFail()->getCompanies() as $companyTransfer) {
            $existingFileAttachmentCompanyIds[] = $companyTransfer->getIdCompanyOrFail();
        }

        $companyAttachmentToAdd = array_diff(
            $formData[AttachFileForm::FIELD_COMPANY_IDS],
            $existingFileAttachmentCompanyIds,
        );

        $fileAttachmentToAdd->setCompanyCollection(new CompanyCollectionTransfer());

        foreach ($companyAttachmentToAdd as $idCompany) {
            $fileAttachmentToAdd->getCompanyCollectionOrFail()->addCompany((new CompanyTransfer())->setIdCompany($idCompany));
        }

        $companyAttachmentToDelete = array_diff(
            $existingFileAttachmentCompanyIds,
            $formData[AttachFileForm::FIELD_COMPANY_IDS],
        );

        $fileAttachmentToDelete->setCompanyCollection(new CompanyCollectionTransfer());

        foreach ($companyAttachmentToDelete as $idCompany) {
            $fileAttachmentToDelete->getCompanyCollectionOrFail()->addCompany((new CompanyTransfer())->setIdCompany($idCompany));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setCompanyUserFileAttachments(
        FileAttachmentTransfer $fileAttachmentTransfer,
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (!isset($formData[AttachFileForm::FIELD_COMPANY_USER_IDS])) {
            return;
        }

        $existingFileAttachmentCompanyUserId = [];
        foreach ($fileAttachmentTransfer->getCompanyUserCollectionOrFail()->getCompanyUsers() as $companyUserTransfer) {
            $existingFileAttachmentCompanyUserId[] = $companyUserTransfer->getIdCompanyUserOrFail();
        }

        $companyUserAttachmentToAdd = array_diff(
            $formData[AttachFileForm::FIELD_COMPANY_USER_IDS],
            $existingFileAttachmentCompanyUserId,
        );

        $fileAttachmentToAdd->setCompanyUserCollection(new CompanyUserCollectionTransfer());

        foreach ($companyUserAttachmentToAdd as $idCompanyUser) {
            $fileAttachmentToAdd->getCompanyUserCollectionOrFail()->addCompanyUser(
                (new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser),
            );
        }

        $companyUserAttachmentToDelete = array_diff(
            $existingFileAttachmentCompanyUserId,
            $formData[AttachFileForm::FIELD_COMPANY_USER_IDS],
        );

        $fileAttachmentToDelete->setCompanyUserCollection(new CompanyUserCollectionTransfer());
        foreach ($companyUserAttachmentToDelete as $idCompanyUser) {
            $fileAttachmentToDelete->getCompanyUserCollectionOrFail()
                ->addCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setSspAssetFileAttachments(
        FileAttachmentTransfer $fileAttachmentTransfer,
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (!isset($formData[SspAssetAttachmentForm::FIELD_ASSET_IDS])) {
            return;
        }

        $existingFileAttachmentSspAssetIds = [];
        foreach ($fileAttachmentTransfer->getSspAssetCollectionOrFail()->getSspAssets() as $sspAssetTransfer) {
            $existingFileAttachmentSspAssetIds[] = $sspAssetTransfer->getIdSspAssetOrFail();
        }

        $sspAssetAttachmentToAdd = array_diff(
            $formData[SspAssetAttachmentForm::FIELD_ASSET_IDS],
            $existingFileAttachmentSspAssetIds,
        );

        $fileAttachmentToAdd->setSspAssetCollection(new SspAssetCollectionTransfer());
        foreach ($sspAssetAttachmentToAdd as $idSspAsset) {
            $fileAttachmentToAdd->getSspAssetCollectionOrFail()
                ->addSspAsset((new SspAssetTransfer())->setIdSspAsset($idSspAsset));
        }

        $sspAssetAttachmentToDelete = array_diff(
            $existingFileAttachmentSspAssetIds,
            $formData[SspAssetAttachmentForm::FIELD_ASSET_IDS],
        );

        $fileAttachmentToDelete->setSspAssetCollection(new SspAssetCollectionTransfer());
        foreach ($sspAssetAttachmentToDelete as $idSspAsset) {
            $fileAttachmentToDelete->getSspAssetCollectionOrFail()
                ->addSspAsset((new SspAssetTransfer())->setIdSspAsset($idSspAsset));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return array<string, array<int>>
     */
    public function mapFileAttachmentCollectionTransferToFormData(FileAttachmentTransfer $fileAttachmentTransfer): array
    {
        $formData = [
            AttachFileForm::FIELD_COMPANY_IDS => [],
            AttachFileForm::FIELD_COMPANY_USER_IDS => [],
            AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS => [],
            SspAssetAttachmentForm::FIELD_ASSET_IDS => [],
        ];

        foreach ($fileAttachmentTransfer->getSspAssetCollectionOrFail()->getSspAssets() as $sspAssetTransfer) {
            $formData[SspAssetAttachmentForm::FIELD_ASSET_IDS][] = $sspAssetTransfer->getIdSspAssetOrFail();
        }
        foreach ($fileAttachmentTransfer->getCompanyCollectionOrFail()->getCompanies() as $companyTransfer) {
            $formData[AttachFileForm::FIELD_COMPANY_IDS][] = $companyTransfer->getIdCompanyOrFail();
        }
        foreach ($fileAttachmentTransfer->getBusinessUnitCollectionOrFail()->getCompanyBusinessUnits() as $businessUnitTransfer) {
            $formData[AttachFileForm::FIELD_COMPANY_BUSINESS_UNIT_IDS][] = $businessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }
        foreach ($fileAttachmentTransfer->getCompanyUserCollectionOrFail()->getCompanyUsers() as $companyUserTransfer) {
            $formData[AttachFileForm::FIELD_COMPANY_USER_IDS][] = $companyUserTransfer->getIdCompanyUserOrFail();
        }

        return $formData;
    }
}
