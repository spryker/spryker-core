<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class AddFileController extends AbstractController
{
    protected const FILE_DIRECTORY_ID = 'file-directory-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getFileForm()
            ->handleRequest($request);

        $redirectUrl = Url::generate(
            $request->get(self::FILE_DIRECTORY_ID) ?
                '/file-manager-gui/directories-tree' :
                '/file-manager-gui'
        )->build();

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $fileManagerDataTransfer = $this->createFileManagerDataTransfer($data);

                    if ($request->get(self::FILE_DIRECTORY_ID)) {
                        $fileManagerDataTransfer->getFile()->setFkFileDirectory($request->get(self::FILE_DIRECTORY_ID));
                    }

                    $this->getFactory()->getFileManagerFacade()->saveFile($fileManagerDataTransfer);

                    $this->addSuccessMessage('The file was added successfully.');
                } catch (Exception $exception) {
                    $this->addErrorMessage($exception->getMessage());
                }
            } else {
                $this->addErrors($form);
            }

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'currentLocale' => $this->getFactory()->getCurrentLocale(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    protected function createFileManagerDataTransfer(FileTransfer $fileTransfer)
    {
        $fileManagerDataTransfer = new FileManagerDataTransfer();
        $this->setFileName($fileTransfer);

        $fileManagerDataTransfer->setFile($fileTransfer);
        $fileManagerDataTransfer->setFileInfo($this->createFileInfoTransfer($fileTransfer));
        $fileManagerDataTransfer->setContent($this->getFileContent($fileTransfer));
        $fileManagerDataTransfer->setFileLocalizedAttributes($fileTransfer->getLocalizedAttributes());

        return $fileManagerDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(FileTransfer $fileTransfer)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $fileTransfer->getFileContent();
        $fileInfo = new FileInfoTransfer();

        $fileInfo->setExtension($uploadedFile->getClientOriginalExtension());
        $fileInfo->setSize($uploadedFile->getSize());
        $fileInfo->setType($uploadedFile->getMimeType());

        return $fileInfo;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function setFileName(FileTransfer $fileTransfer)
    {
        if ($fileTransfer->getUseRealName()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
            $uploadedFile = $fileTransfer->getFileContent();
            $fileTransfer->setFileName($uploadedFile->getClientOriginalName());
        }

        return $fileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return bool|string
     */
    protected function getFileContent(FileTransfer $fileTransfer)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $fileTransfer->getFileContent();

        return file_get_contents($uploadedFile->getRealPath());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrors(FormInterface $form)
    {
        $errors = $form->getErrors(true);

        do {
            $this->addErrorMessage(
                $errors->current()->getMessage()
            );
        } while ($errors->next());
    }
}
