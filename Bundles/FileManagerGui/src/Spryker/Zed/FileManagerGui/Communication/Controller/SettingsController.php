<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Zed\FileManagerGui\Communication\Form\MimeTypeSettingsForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class SettingsController extends AbstractController
{
    const FORM_DATA_KEY_ID_MIME_TYPE = 'idMimeType';
    const FORM_DATA_KEY_IS_ALLOWED = 'isAllowed';

    public function indexAction(Request $request)
    {
        $mimeTypeSettingsTable = $this->getFactory()->createMimeTypeSettingsTable();
        $mimeTypeSettingsForm = $this->getFactory()
            ->getMimeTypeSettingsForm()
            ->handleRequest($request);

        if ($mimeTypeSettingsForm->isSubmitted()) {
            $formData = $mimeTypeSettingsForm->getData();

            if ($formData !== null) {
                $this->getFactory()
                    ->getFileManagerFacade()
                    ->updateMimeTypeSettings(
                        $this->createMimeTypeCollectionTransfer($formData)
                    );
            }
        }

        return [
            'mimeTypeSettings' => $mimeTypeSettingsTable->render(),
            'mimeTypeSettingsForm' => $mimeTypeSettingsForm->createView(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createMimeTypeSettingsTable();

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\MimeTypeCollectionTransfer
     */
    protected function createMimeTypeCollectionTransfer(array $formData)
    {
        $mimeTypeCollectionTransfer = new MimeTypeCollectionTransfer();
        $formData = json_decode($formData[MimeTypeSettingsForm::FIELD_MIME_TYPES]);

        foreach ($formData as $mimeType) {
            $mimeTypeTransfer = new MimeTypeTransfer();
            $mimeTypeTransfer->setIdMimeType($mimeType->{static::FORM_DATA_KEY_ID_MIME_TYPE});
            $mimeTypeTransfer->setIsAllowed($mimeType->{static::FORM_DATA_KEY_IS_ALLOWED});

            $mimeTypeCollectionTransfer->addMimeType($mimeTypeTransfer);
        }

        return $mimeTypeCollectionTransfer;
    }
}
