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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class AddFileController extends AbstractUploadFileController
{
    /**
     * @var string
     */
    protected const FILE_DIRECTORY_ID = 'file-directory-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getFileForm()
            ->handleRequest($request);

        $redirectUrl = Url::generate(
            $request->get(static::FILE_DIRECTORY_ID) ?
                '/file-manager-gui/directories-tree' :
                '/file-manager-gui',
        )->build();

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $fileManagerDataTransfer = $this->createFileManagerDataTransfer($data);

                    if ($request->get(static::FILE_DIRECTORY_ID)) {
                        $fileManagerDataTransfer->getFileOrFail()->setFkFileDirectory($request->get(static::FILE_DIRECTORY_ID));
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
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileInfoTransfer
     */
    protected function createFileInfoTransfer(FileTransfer $fileTransfer)
    {
        $fileInfo = new FileInfoTransfer();
        $fileUploadTransfer = $fileTransfer->getFileUpload();

        if ($fileUploadTransfer === null) {
            return $fileInfo;
        }

        $fileInfo->setExtension($fileUploadTransfer->getClientOriginalExtension());
        $fileInfo->setSize($fileUploadTransfer->getSize());
        $fileInfo->setType($fileUploadTransfer->getMimeTypeName());

        return $fileInfo;
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Generated\Shared\Transfer\FileTransfer
     */
    protected function setFileName(FileTransfer $fileTransfer)
    {
        $fileUploadTransfer = $fileTransfer->getFileUpload();

        if ($fileUploadTransfer === null) {
            return $fileTransfer;
        }

        if ($fileTransfer->getUseRealName()) {
            $fileTransfer->setFileName(
                $fileUploadTransfer->getClientOriginalName(),
            );
        }

        return $fileTransfer;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrors(FormInterface $form)
    {
        foreach ($form->getErrors(true) as $error) {
            /** @var \Symfony\Component\Form\FormError $error */
            $this->addErrorMessage($error->getMessage());
        }
    }
}
