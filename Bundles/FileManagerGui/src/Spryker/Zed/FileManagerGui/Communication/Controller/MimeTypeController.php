<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\MimeTypeCollectionTransfer;
use Generated\Shared\Transfer\MimeTypeTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\FileManagerGui\Communication\Form\MimeTypeSettingsForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory getFactory()
 */
class MimeTypeController extends AbstractController
{
    protected const ROUTE_MIME_TYPE_INDEX = '/file-manager-gui/mime-type';

    protected const URL_PARAM_ID_MIME_TYPE = 'id-mime-type';

    protected const MESSAGE_MIME_TYPE_SAVING_SUCCESS = 'MIME type was successfully saved';
    protected const MESSAGE_MIME_TYPE_SAVING_ERROR = 'MIME type already exists';
    protected const MESSAGE_MIME_TYPE_DELETING_SUCCESS = 'MIME type was successfully deleted';
    protected const MESSAGE_MIME_TYPE_DELETING_ERROR = 'Failed to delete MIME type';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $table = $this->getFactory()->createMimeTypeSettingsTable();
        $form = $this->getFactory()
            ->getMimeTypeSettingsForm()
            ->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $form->getData();

            if ($formData[MimeTypeSettingsForm::FIELD_MIME_TYPES] !== null) {
                $this->getFactory()
                    ->getFileManagerFacade()
                    ->updateMimeTypeSettings(
                        $this->createMimeTypeCollectionTransfer($formData)
                    );
            }
        }

        return [
            'table' => $table->render(),
            'form' => $form->createView(),
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->getMimeTypeForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mimeTypeResponseTransfer = $this->saveMimeType($form);

            if ($mimeTypeResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponse(
                    Url::generate(static::ROUTE_MIME_TYPE_INDEX)->build()
                );
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request)
    {
        $form = $this->getFactory()
            ->getMimeTypeForm($request->get(static::URL_PARAM_ID_MIME_TYPE))
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mimeTypeResponseTransfer = $this->saveMimeType($form);

            if ($mimeTypeResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponse(
                    Url::generate(static::ROUTE_MIME_TYPE_INDEX)->build()
                );
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $mimeTypeTransfer = new MimeTypeTransfer();
        $mimeTypeTransfer->setIdMimeType($request->get(static::URL_PARAM_ID_MIME_TYPE));

        $mimeTypeResponseTransfer = $this->getFactory()
            ->getFileManagerFacade()
            ->deleteMimeType($mimeTypeTransfer);

        $mimeTypeResponseTransfer->getIsSuccessful() ?
            $this->addSuccessMessage(static::MESSAGE_MIME_TYPE_DELETING_SUCCESS) :
            $this->addErrorMessage(static::MESSAGE_MIME_TYPE_DELETING_ERROR);

        return $this->redirectResponse(
            Url::generate(static::ROUTE_MIME_TYPE_INDEX)->build()
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Generated\Shared\Transfer\MimeTypeResponseTransfer
     */
    protected function saveMimeType(FormInterface $form)
    {
        $mimeTypeResponseTransfer = $this->getFactory()
            ->getFileManagerFacade()
            ->saveMimeType(
                $form->getData()
            );

        $mimeTypeResponseTransfer->getIsSuccessful() ?
            $this->addSuccessMessage(static::MESSAGE_MIME_TYPE_SAVING_SUCCESS) :
            $this->addErrorMessage(static::MESSAGE_MIME_TYPE_SAVING_ERROR);

        return $mimeTypeResponseTransfer;
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
            $mimeTypeTransfer->setIdMimeType($mimeType->{MimeTypeSettingsForm::FORM_DATA_KEY_ID_MIME_TYPE});
            $mimeTypeTransfer->setIsAllowed($mimeType->{MimeTypeSettingsForm::FORM_DATA_KEY_IS_ALLOWED});

            $mimeTypeCollectionTransfer->addMimeType($mimeTypeTransfer);
        }

        return $mimeTypeCollectionTransfer;
    }
}
