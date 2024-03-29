<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class EditFileController extends AbstractUploadFileController
{
    /**
     * @var string
     */
    protected const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idFile = $request->get(static::URL_PARAM_ID_FILE);
        $form = $this->getFactory()
            ->getFileForm($idFile)
            ->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $data = $form->getData();
                $fileManagerDataTransfer = $this->createFileManagerDataTransfer($data);

                $this->getFactory()->getFileManagerFacade()->saveFile($fileManagerDataTransfer);

                $this->addSuccessMessage(
                    'The file was edited successfully.',
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
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
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
            $request->get(static::URL_PARAM_ID_FILE),
        );

        $fileInfoTable = $this
            ->getFactory()
            ->createFileInfoEditTable($idFile);

        return $this->jsonResponse(
            $fileInfoTable->fetchData(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function setFileName(FileTransfer $fileTransfer)
    {
        if (!$fileTransfer->getFileName()) {
            $fileUploadTransfer = $fileTransfer->getFileUpload();

            if ($fileUploadTransfer !== null) {
                $fileTransfer->setFileName($fileUploadTransfer->getClientOriginalName());
            }
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
        $fileUploadTransfer = $fileTransfer->getFileUpload();
        $fileInfoTransfer = new FileInfoTransfer();

        if ($fileUploadTransfer === null) {
            return $fileInfoTransfer;
        }

        $fileInfoTransfer->setFkFile($fileTransfer->getIdFile());
        $fileInfoTransfer->setExtension($fileUploadTransfer->getClientOriginalExtension());
        $fileInfoTransfer->setSize($fileUploadTransfer->getSize());
        $fileInfoTransfer->setType($fileUploadTransfer->getMimeTypeName());

        return $fileInfoTransfer;
    }

    /**
     * @param array<string, mixed> $data
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
}
