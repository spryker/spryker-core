<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface getFileManagerFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class DeleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_FILE_REMOVED = 'File was successfully removed.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_CANNOT_BE_REMOVED = 'File cannot be removed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idFile = $request->query->getInt(static::REQUEST_PARAM_ID_FILE);

        $form = $this->getFactory()->createDeleteFileForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_CSRF_TOKEN_INVALID);

            return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
        }

        $fileTransfer = $this->getFactory()->createFileReader()->findFileByIdFile($idFile);

        if ($fileTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idFile,
            ]);

            return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
        }

        $response = $this->getFactory()->getFileManagerFacade()->deleteFile($fileTransfer->getIdFileOrFail());
        if (!$response) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_CANNOT_BE_REMOVED);

            return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_FILE_REMOVED);

        return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function confirmDeleteAction(Request $request): array|RedirectResponse
    {
        $idFile = $request->query->getInt(static::REQUEST_PARAM_ID_FILE);

        $fileTransfer = $this->getFactory()->createFileReader()->findFileByIdFile($idFile);

        if ($fileTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idFile,
            ]);

            return $this->redirectResponse(static::URL_SSP_FILE_MANAGEMENT_LIST);
        }

        return $this->viewResponse([
            'file' => $fileTransfer,
            'urlSspFileManagementDelete' => static::URL_SSP_FILE_MANAGEMENT_DELETE,
            'deleteFileForm' => $this->getFactory()->createDeleteFileForm()->createView(),
        ]);
    }
}
