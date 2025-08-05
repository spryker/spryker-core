<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Mapper;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\SspModelForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\SspModelImageForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SspModelFormDataToTransferMapper implements SspModelFormDataToTransferMapperInterface
{
    public function mapFormDataToSspModelTransfer(FormInterface $form, SspModelTransfer $sspModelTransfer): SspModelTransfer
    {
        $imageFile = $form->get(SspModelForm::FIELD_IMAGE)->get(SspModelImageForm::FIELD_FILE)->getData();
        if ($imageFile instanceof UploadedFile) {
            $sspModelTransfer->setImage(
                (new FileTransfer())
                    ->setFileUpload($this->createFileUploadTransfer($imageFile))
                    ->setFileContent($this->getFileContent($imageFile)),
            );
        }

        return $sspModelTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     *
     * @throws \Exception
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
            return '';
        }

        return $fileContent;
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
}
