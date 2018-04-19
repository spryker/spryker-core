<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class AddFileController extends AbstractController
{
    const FILE_DIRECTORY_ID = 'file-directory-id';

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

        if ($form->isValid()) {
            try {
                $data = $form->getData();
                $saveRequestTransfer = $this->createFileManagerSaveRequestTransfer($data);

                if ($request->get(self::FILE_DIRECTORY_ID)) {
                    $saveRequestTransfer->getFile()->setFkFileDirectory($request->get(self::FILE_DIRECTORY_ID));
                }

                $this->getFactory()->getFileManagerFacade()->saveFile($saveRequestTransfer);

                $this->addSuccessMessage(
                    'The file was added successfully.'
                );
                $redirectUrl = Url::generate('/file-manager-gui/directories-tree')->build();

                return $this->redirectResponse($redirectUrl);
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
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
     * @return \Generated\Shared\Transfer\FileManagerSaveRequestTransfer
     */
    protected function createFileManagerSaveRequestTransfer(FileTransfer $fileTransfer)
    {
        $requestTransfer = new FileManagerSaveRequestTransfer();
        $this->setFileName($fileTransfer);

        $requestTransfer->setFile($fileTransfer);
        $requestTransfer->setFileInfo($this->createFileInfoTransfer($fileTransfer));
        $requestTransfer->setContent($this->getFileContent($fileTransfer));
        $requestTransfer->setFileLocalizedAttributes($fileTransfer->getFileLocalizedAttributes());

        return $requestTransfer;
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

        $fileInfo->setFileExtension($uploadedFile->getClientOriginalExtension());
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
}
