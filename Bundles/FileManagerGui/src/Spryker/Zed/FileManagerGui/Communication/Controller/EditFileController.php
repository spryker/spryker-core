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
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class EditFileController extends AbstractController
{
    const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idFile = $request->get(static::URL_PARAM_ID_FILE);
        $form = $this->getFactory()
            ->getFileForm($idFile)
            ->handleRequest($request);

        if ($form->isValid()) {
            try {
                $data = $form->getData();
                $saveRequestTransfer = $this->createFileManagerSaveRequestTransfer($data);

                $this->getFactory()->getFileManagerFacade()->save($saveRequestTransfer);

                $this->addSuccessMessage(
                    'The file was edited successfully.'
                );
                $redirectUrl = Url::generate(sprintf('/file-manager-gui/edit-file?id-file=%d', $idFile))->build();

                return $this->redirectResponse($redirectUrl);
            } catch (Exception $exception) {
                $this->addErrorMessage($exception->getMessage());
            }
        }

        $fileInfoTable = $this->getFactory()->createFileInfoEditTable($idFile);
        $fileFormsTabs = $this->getFactory()->createFileFormTabs();

        return [
            'fileFormTabs' => $fileFormsTabs->createView(),
            'fileInfoTable' => $fileInfoTable->render(),
            'fileForm' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'currentLocale' => $this->getFactory()->getCurrentLocale(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function fileInfoTableAction(Request $request)
    {
        $idFile = $this->castId(
            $request->get(static::URL_PARAM_ID_FILE)
        );

        $fileInfoTable = $this
            ->getFactory()
            ->createFileInfoEditTable($idFile);

        return $this->jsonResponse(
            $fileInfoTable->fetchData()
        );
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
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function setFileName(FileTransfer $fileTransfer)
    {
        if (!$fileTransfer->getFileName()) {
            $fileTransfer->setFileName($fileTransfer->getFileContent()->getClientOriginalName());
        }

        return $fileTransfer;
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
        $fileInfoTransfer = new FileInfoTransfer();

        if ($uploadedFile === null) {
            return $fileInfoTransfer;
        }

        $fileInfoTransfer->setFkFile($fileTransfer->getIdFile());
        $fileInfoTransfer->setExtension($uploadedFile->getClientOriginalExtension());
        $fileInfoTransfer->setSize($uploadedFile->getSize());
        $fileInfoTransfer->setType($uploadedFile->getMimeType());

        return $fileInfoTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(array $data)
    {
        $fileTransfer = new FileTransfer();
        $fileTransfer->setFileName($data[FileForm::FIELD_FILE_NAME]);
        $fileTransfer->setIdFile($data[FileForm::FIELD_ID_FILE]);

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

        if ($uploadedFile === null) {
            return null;
        }

        return file_get_contents($uploadedFile->getRealPath());
    }
}
