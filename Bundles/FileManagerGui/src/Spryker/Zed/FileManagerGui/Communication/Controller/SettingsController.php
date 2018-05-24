<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileTypeCollectionTransfer;
use Generated\Shared\Transfer\FileTypeTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\FileTypeForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class SettingsController extends AbstractController
{
    const FORM_DATA_KEY_ID_FILE_TYPE = 'idFileType';
    const FORM_DATA_KEY_IS_ALLOWED = 'isAllowed';

    public function indexAction(Request $request)
    {
        $fileTypeSettingsTable = $this->getFactory()->createFileTypeSettingsTable();
        $fileTypeForm = $this->getFactory()
            ->getFileTypeForm()
            ->handleRequest($request);

        if ($fileTypeForm->isSubmitted()) {
            $formData = $fileTypeForm->getData();

            if ($formData !== null) {
                $this->getFactory()
                    ->getFileManagerFacade()
                    ->updateFileTypeSettings(
                        $this->createFileTypeCollectionTransfer($formData)
                    );
            }
        }

        return [
            'fileTypeSettings' => $fileTypeSettingsTable->render(),
            'fileTypeForm' => $fileTypeForm->createView(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createFileTypeSettingsTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\FileTypeCollectionTransfer
     */
    protected function createFileTypeCollectionTransfer(array $formData)
    {
        $fileTypeCollectionTransfer = new FileTypeCollectionTransfer();
        $formData = json_decode($formData[FileTypeForm::FIELD_FILE_TYPES]);

        foreach ($formData as $fileType) {
            $fileTypeTransfer = new FileTypeTransfer();
            $fileTypeTransfer->setIdFileType($fileType->{static::FORM_DATA_KEY_ID_FILE_TYPE});
            $fileTypeTransfer->setIsAllowed($fileType->{static::FORM_DATA_KEY_IS_ALLOWED});

            $fileTypeCollectionTransfer->addFileType($fileTypeTransfer);
        }

        return $fileTypeCollectionTransfer;
    }
}
