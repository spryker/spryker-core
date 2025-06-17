<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class DeleteFileController extends FileAbstractController
{
    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_FILE_DELETED = 'File was successfully removed.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FILE_CANNOT_BE_DELETED = 'File cannot be removed.';

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

            return $this->redirectResponse(static::URL_PATH_LIST_FILE);
        }

        $fileTransfer = $this->getFactory()->createFileReader()->findFileByIdFile($idFile);

        if ($fileTransfer === null) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idFile,
            ]);

            return $this->redirectResponse(static::URL_PATH_LIST_FILE);
        }

        $response = $this->getFactory()->getFileManagerFacade()->deleteFile($fileTransfer->getIdFileOrFail());
        if (!$response) {
            $this->addErrorMessage(static::ERROR_MESSAGE_FILE_CANNOT_BE_DELETED);

            return $this->redirectResponse(static::URL_PATH_LIST_FILE);
        }

        $this->addSuccessMessage(static::SUCCESS_MESSAGE_FILE_DELETED);

        return $this->redirectResponse(static::URL_PATH_LIST_FILE);
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

            return $this->redirectResponse(static::URL_PATH_LIST_FILE);
        }

        return $this->viewResponse([
            'file' => $fileTransfer,
            'urlPathDeleteFile' => static::URL_PATH_DELETE_FILE,
            'deleteFileForm' => $this->getFactory()->createDeleteFileForm()->createView(),
        ]);
    }
}
