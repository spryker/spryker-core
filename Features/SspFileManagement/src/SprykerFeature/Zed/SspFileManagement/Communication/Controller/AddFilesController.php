<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Exception;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\UploadFileForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class AddFilesController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_FILES_WERE_ADDED_SUCCESSFULLY = 'The files were added successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request): array|RedirectResponse
    {
        $form = $this->getFactory()
            ->createUploadFileForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleValidForm($form);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addErrors($form);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'urlSspFileManagementList' => static::URL_SSP_FILE_MANAGEMENT_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleValidForm(FormInterface $form): RedirectResponse
    {
        $redirectUrl = Url::generate(static::URL_SSP_FILE_MANAGEMENT_LIST)->build();

        try {
            $uploadedFiles = $form->get(UploadFileForm::FIELD_FILE_UPLOAD)->getData();

            $this->getFactory()
                ->createFileSaver()
                ->saveFiles($uploadedFiles);

            $this->addSuccessMessage(static::MESSAGE_FILES_WERE_ADDED_SUCCESSFULLY);

            return $this->redirectResponse($redirectUrl);
        } catch (Exception $exception) {
            $this->addErrorMessage($exception->getMessage());
        }

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrors(FormInterface $form): void
    {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
