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

        $this->setCompanyUserFileAttachments($formData, $fileAttachmentToAdd, $fileAttachmentToDelete);
        $this->setBusinessUnitFileAttachments($formData, $fileAttachmentToAdd, $fileAttachmentToDelete);
        $this->setSspAssetFileAttachments($formData, $fileAttachmentToAdd, $fileAttachmentToDelete);

        return (new FileAttachmentCollectionRequestTransfer())
            ->addFileAttachmentToAdd($fileAttachmentToAdd)
            ->addFileAttachmentToDelete($fileAttachmentToDelete);
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setBusinessUnitFileAttachments(
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (isset($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED]) && !empty($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED])) {
            $fileAttachmentToAdd->setBusinessUnitCollection(new CompanyBusinessUnitCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_ASSIGNED] as $idCompanyBusinessUnit) {
                if ($idCompanyBusinessUnit) {
                    $fileAttachmentToAdd->getBusinessUnitCollectionOrFail()->addCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit((int)$idCompanyBusinessUnit),
                    );
                }
            }
        }

        if (isset($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_DEASSIGNED]) && !empty($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_DEASSIGNED])) {
            $fileAttachmentToDelete->setBusinessUnitCollection(new CompanyBusinessUnitCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_BUSINESS_UNIT_IDS_TO_BE_DEASSIGNED] as $idCompanyBusinessUnit) {
                if ($idCompanyBusinessUnit) {
                    $fileAttachmentToDelete->getBusinessUnitCollectionOrFail()->addCompanyBusinessUnit(
                        (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit((int)$idCompanyBusinessUnit),
                    );
                }
            }
        }
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setCompanyFileAttachments(
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (isset($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_ASSIGNED]) && !empty($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_ASSIGNED])) {
            $fileAttachmentToAdd->setCompanyCollection(new CompanyCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_ASSIGNED] as $idCompany) {
                if ($idCompany) {
                    $fileAttachmentToAdd->getCompanyCollectionOrFail()->addCompany(
                        (new CompanyTransfer())->setIdCompany((int)$idCompany),
                    );
                }
            }
        }

        if (isset($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_DEASSIGNED]) && !empty($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_DEASSIGNED])) {
            $fileAttachmentToDelete->setCompanyCollection(new CompanyCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_COMPANY_IDS_TO_BE_DEASSIGNED] as $idCompany) {
                if ($idCompany) {
                    $fileAttachmentToDelete->getCompanyCollectionOrFail()->addCompany(
                        (new CompanyTransfer())->setIdCompany((int)$idCompany),
                    );
                }
            }
        }
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setCompanyUserFileAttachments(
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (isset($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_ASSIGNED]) && !empty($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_ASSIGNED])) {
            $fileAttachmentToAdd->setCompanyUserCollection(new CompanyUserCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_ASSIGNED] as $idCompanyUser) {
                if ($idCompanyUser) {
                    $fileAttachmentToAdd->getCompanyUserCollectionOrFail()->addCompanyUser(
                        (new CompanyUserTransfer())->setIdCompanyUser((int)$idCompanyUser),
                    );
                }
            }
        }

        if (isset($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_DEASSIGNED]) && !empty($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_DEASSIGNED])) {
            $fileAttachmentToDelete->setCompanyUserCollection(new CompanyUserCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_COMPANY_USER_IDS_TO_BE_DEASSIGNED] as $idCompanyUser) {
                if ($idCompanyUser) {
                    $fileAttachmentToDelete->getCompanyUserCollectionOrFail()->addCompanyUser(
                        (new CompanyUserTransfer())->setIdCompanyUser((int)$idCompanyUser),
                    );
                }
            }
        }
    }

    /**
     * @param array<mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToAdd
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentToDelete
     *
     * @return void
     */
    protected function setSspAssetFileAttachments(
        array $formData,
        FileAttachmentTransfer $fileAttachmentToAdd,
        FileAttachmentTransfer $fileAttachmentToDelete
    ): void {
        if (isset($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_ASSIGNED]) && !empty($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_ASSIGNED])) {
            $fileAttachmentToAdd->setSspAssetCollection(new SspAssetCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_ASSIGNED] as $idSspAsset) {
                if ($idSspAsset) {
                    $fileAttachmentToAdd->getSspAssetCollectionOrFail()
                        ->addSspAsset((new SspAssetTransfer())->setIdSspAsset((int)$idSspAsset));
                }
            }
        }

        if (isset($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_DEASSIGNED]) && !empty($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_DEASSIGNED])) {
            $fileAttachmentToDelete->setSspAssetCollection(new SspAssetCollectionTransfer());
            foreach ($formData[AttachFileForm::FIELD_ASSET_IDS_TO_BE_DEASSIGNED] as $idSspAsset) {
                if ($idSspAsset) {
                    $fileAttachmentToDelete->getSspAssetCollectionOrFail()
                        ->addSspAsset((new SspAssetTransfer())->setIdSspAsset((int)$idSspAsset));
                }
            }
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
            AttachFileForm::FIELD_ASSET_IDS => [],
        ];

        foreach ($fileAttachmentTransfer->getSspAssetCollectionOrFail()->getSspAssets() as $sspAssetTransfer) {
            $formData[AttachFileForm::FIELD_ASSET_IDS][] = $sspAssetTransfer->getIdSspAssetOrFail();
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
