<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Exception;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UploadFileForm;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AddFileController extends FileAbstractController
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
            'urlPathListFile' => static::URL_PATH_LIST_FILE,
        ]);
    }

    protected function handleValidForm(FormInterface $form): RedirectResponse
    {
        $redirectUrl = Url::generate(static::URL_PATH_LIST_FILE)->build();

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

    protected function addErrors(FormInterface $form): void
    {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
