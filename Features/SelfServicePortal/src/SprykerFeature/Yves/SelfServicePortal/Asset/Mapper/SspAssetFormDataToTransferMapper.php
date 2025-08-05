<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Mapper;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetForm;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetImageForm;
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
                ->setEncodedContent(base64_encode(gzencode($this->getFileContent($uploadedFile)) ?: ''));
        }

        $sspAssetTransfer->getImageOrFail()->setToDelete($this->shouldExistingAssetImageBeDeleted($sspAssetForm));

        return $sspAssetTransfer;
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
