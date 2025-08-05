<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper;

use Exception;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetImageForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SspAssetFormDataToTransferMapper implements SspAssetFormDataToTransferMapperInterface
{
    public function mapFormDataToSspAssetTransfer(FormInterface $sspAssetForm, SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $uploadedFile = $sspAssetForm->get(SspAssetForm::FIELD_IMAGE)->get(SspAssetImageForm::FIELD_FILE)->getData();

        if (!$sspAssetTransfer->getImage()) {
            $sspAssetTransfer->setImage(new FileTransfer());
        }

        if ($uploadedFile instanceof UploadedFile) {
            $sspAssetTransfer->getImageOrFail()
                ->setFileUpload($this->createFileUploadTransfer($uploadedFile))
                ->setFileContent($this->getFileContent($uploadedFile));
        }

        $sspAssetTransfer->getImageOrFail()->setToDelete($this->shouldExistingAssetImageBeDeleted($sspAssetForm));

        return $sspAssetTransfer;
    }

    /**
     * @param array<int> $assignedBusinessUnitIds
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer
     */
    public function mapAssignmentsToSspAssetCollectionRequestTransfer(
        array $assignedBusinessUnitIds,
        SspAssetTransfer $sspAssetTransfer,
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionRequestTransfer {
        $assignmentsToCompanyBusinessUnitMapping = [];
        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $initialBusinessUnitAssignment) {
            if ($initialBusinessUnitAssignment->getCompanyBusinessUnit() === null) {
                continue;
            }
            $assignmentsToCompanyBusinessUnitMapping[] = $initialBusinessUnitAssignment->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail();
        }

        $businessUnitIdsToAssign = array_diff($assignedBusinessUnitIds, $assignmentsToCompanyBusinessUnitMapping);
        $businessUnitIdsToUnAssign = array_diff($assignmentsToCompanyBusinessUnitMapping, $assignedBusinessUnitIds);

        foreach ($businessUnitIdsToAssign as $businessUnitIdToAssign) {
            $sspAssetCollectionRequestTransfer->addBusinessUnitAssignmentToAdd(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitIdToAssign))
                    ->setSspAsset($sspAssetTransfer),
            );
        }

        foreach ($businessUnitIdsToUnAssign as $businessUnitIdToUnAssign) {
            $sspAssetCollectionRequestTransfer->addBusinessUnitAssignmentToDelete(
                (new SspAssetBusinessUnitAssignmentTransfer())
                    ->setCompanyBusinessUnit((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitIdToUnAssign))
                    ->setSspAsset($sspAssetTransfer),
            );
        }

        return $sspAssetCollectionRequestTransfer;
    }

    protected function createFileUploadTransfer(UploadedFile $uploadedFile): FileUploadTransfer
    {
        return (new FileUploadTransfer())
            ->setClientOriginalName($uploadedFile->getClientOriginalName())
            ->setRealPath((string)$uploadedFile->getRealPath())
            ->setMimeTypeName($uploadedFile->getMimeType())
            ->setClientOriginalExtension($uploadedFile->getClientOriginalExtension())
            ->setSize($uploadedFile->getSize());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @throws \Exception
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     *
     * @return string
     */
    protected function getFileContent(UploadedFile $uploadedFile): string
    {
        $realPath = $uploadedFile->getRealPath();

        if (!$realPath) {
            throw new Exception('Real path not found');
        }

        $fileContent = file_get_contents($realPath);

        if ($fileContent === false) {
            throw new FileNotFoundException($realPath);
        }

        return $fileContent;
    }

    protected function shouldExistingAssetImageBeDeleted(FormInterface $sspAssetForm): bool
    {
        $imageField = $sspAssetForm->get(SspAssetForm::FIELD_IMAGE);

        return $imageField->get(SspAssetImageForm::FIELD_FILE)->getData() instanceof UploadedFile || $imageField->get(SspAssetImageForm::FIELD_DELETE)->getData();
    }
}
