<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Controller;

use Generated\Shared\Transfer\ApiKeyCollectionDeleteCriteriaTransfer;
use Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ApiKeyGui\Communication\ApiKeyGuiCommunicationFactory getFactory()
 */
class DeleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_ID_API_KEY = 'id';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_API_KEY_DOES_NOT_EXIST = 'API Key with ID `%d` does not exist.';

    /**
     * @var string
     */
    protected const MESSAGE_API_KEY_DELETE_SUCCESS = 'API key has been successfully removed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $idApiKey = $request->get(static::REQUEST_ID_API_KEY);

        if (!$idApiKey) {
            throw new NotFoundHttpException(static::MESSAGE_ERROR_API_KEY_DOES_NOT_EXIST);
        }

        $deleteForm = $this->getFactory()->createDeleteApiKeyForm()->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
        }
        if (!$deleteForm->isSubmitted() || !$deleteForm->isValid()) {
            foreach ($deleteForm->getErrors(true) as $formError) {
                /** @var \Symfony\Component\Form\FormError $formError */
                $this->addErrorMessage($formError->getMessage(), $formError->getMessageParameters());
            }

            return $this->viewResponse([
                'deleteForm' => $deleteForm->createView(),
            ]);
        }

        $apiKeyCollectionResponseTransfer = $this->getFactory()
            ->getApiKeyFacade()
            ->deleteApiKeyCollection(
                (new ApiKeyCollectionDeleteCriteriaTransfer())
                    ->addIdApiKey($idApiKey),
            );

        if ($apiKeyCollectionResponseTransfer->getErrors()->count() === 0) {
            $this->addSuccessMessage(static::MESSAGE_API_KEY_DELETE_SUCCESS);

            return $this->redirectResponse(ApiKeyGuiConfig::URL_API_KEY_LIST);
        }

        foreach ($apiKeyCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail(), $errorTransfer->getParameters());
        }

        return $this->viewResponse([
            'deleteForm' => $deleteForm->createView(),
        ]);
    }
}
