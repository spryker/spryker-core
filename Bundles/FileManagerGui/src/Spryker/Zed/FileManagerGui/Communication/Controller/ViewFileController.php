<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class ViewFileController extends AbstractController
{
    const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idFile = $this->castId($request->get(static::URL_PARAM_ID_FILE));

        $file = $this->getFactory()
            ->getFileManagerQueryContainer()
            ->queryFileById($idFile)
            ->findOne();
        $fileInfoTable = $this->getFactory()->createFileInfoViewTable($idFile);

        return [
            'file' => $file,
            'fileInfoTable' => $fileInfoTable->render(),
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
            ->createFileInfoViewTable($idFile);

        return $this->jsonResponse(
            $fileInfoTable->fetchData()
        );
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileManagerSaveRequestTransfer
     */
    protected function createFileManagerSaveRequestTransfer(array $data)
    {
        $requestTransfer = new FileManagerSaveRequestTransfer();
        $requestTransfer->setFile($this->createFileTransfer($data));
        $requestTransfer->setFileInfo($this->createFileInfoTransfer($data));
        $requestTransfer->setContent($this->getFileContent($data));

        return $requestTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(array $data)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];
        $fileInfo = new FileInfoTransfer();

        if ($uploadedFile === null) {
            return $fileInfo;
        }

        $fileInfo->setFileExtension($uploadedFile->getClientOriginalExtension());
        $fileInfo->setSize($uploadedFile->getSize());
        $fileInfo->setType($uploadedFile->getMimeType());
        $fileInfo->setFkFile($data[FileForm::FIELD_ID_FILE]);

        return $fileInfo;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function createFileTransfer(array $data)
    {
        $file = new FileTransfer();
        $file->setFileName($data[FileForm::FIELD_FILE_NAME]);
        $file->setIdFile($data[FileForm::FIELD_ID_FILE]);

        return $file;
    }

    /**
     * @param array $data
     *
     * @return bool|string
     */
    protected function getFileContent(array $data)
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];

        if ($uploadedFile === null) {
            return null;
        }

        return file_get_contents($uploadedFile->getRealPath());
    }
}
