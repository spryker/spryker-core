<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Controller;

use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Generated\Shared\Transfer\ApiKeyTransfer;
use Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ApiKeyGui\Communication\ApiKeyGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_ID_API_KEY = 'id';

    /**
     * @var string
     */
    protected const ID_API_KEY = 'id_api_key';

    /**
     * @var string
     */
    protected const KEY_FORM = 'form';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_API_KEY_DOES_NOT_EXIST = 'API Key with ID `%d` does not exist.';

    /**
     * @var string
     */
    protected const MESSAGE_ID_PLACEHOLDER = '%d';

    /**
     * @var string
     */
    protected const MESSAGE_KEY_PLACEHOLDER = '%s';

    /**
     * @var string
     */
    protected const MESSAGE_API_KEY_UPDATED = 'API key updated successfully. ';

    /**
     * @var string
     */
    protected const API_KEY_WARNING_MESSAGE = 'The new API key is `%s`. Note, it will not be shown again. Please ensure you save it securely for future use.';

    /**
     * @var string
     */
    protected const FIELD_IS_KEY_NEEDS_REGENERATION = 'is_key_needs_regeneration';

    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_VALID_TO = 'valid_to';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $idApiKey = $request->get(static::REQUEST_ID_API_KEY);

        $apiKeyData = $this->getFactory()->createEditApiKeyFormDataProvider()
            ->getData($idApiKey);

        if ($apiKeyData === null) {
            $this->addErrorMessage(static::MESSAGE_ERROR_API_KEY_DOES_NOT_EXIST, [
                static::MESSAGE_ID_PLACEHOLDER => $idApiKey,
            ]);

            return $this->redirectResponse(ApiKeyGuiConfig::URL_API_KEY_LIST);
        }

        $editApiKeyForm = $this->getFactory()
            ->getEditApiKeyForm($apiKeyData)
            ->handleRequest($request);

        if ($editApiKeyForm->isSubmitted() && $editApiKeyForm->isValid()) {
            return $this->updateApiKey($editApiKeyForm);
        }

        return $this->viewResponse([
            static::KEY_FORM => $editApiKeyForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $editApiKeyForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    protected function updateApiKey(FormInterface $editApiKeyForm)
    {
        $key = $editApiKeyForm->getData()[static::FIELD_IS_KEY_NEEDS_REGENERATION] ?
            $this->getFactory()->createApiKeyGenerator()->generate() :
            null;

        $apiKeyCollectionRequestTransfer = (new ApiKeyCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->addApiKey(
                (new ApiKeyTransfer())
                    ->setIdApiKey($editApiKeyForm->getData()[static::ID_API_KEY])
                    ->setName($editApiKeyForm->getData()[static::FIELD_NAME])
                    ->setValidTo($editApiKeyForm->getData()[static::FIELD_VALID_TO])
                    ->setKey($key),
            );

        $apiKeyCollectionResponseTransfer = $this->getFactory()
            ->getApiKeyFacade()
            ->updateApiKeyCollection($apiKeyCollectionRequestTransfer);

        if ($apiKeyCollectionResponseTransfer->getErrors()->count() === 0) {
            $successMessage = static::MESSAGE_API_KEY_UPDATED;
            $successMessageParameters = [];

            $apiKeyTransfer = $apiKeyCollectionResponseTransfer->getApiKeys()->offsetGet(0);

            if ($apiKeyTransfer->getKey() !== null) {
                $successMessage .= static::API_KEY_WARNING_MESSAGE;
                $successMessageParameters = [
                    static::MESSAGE_KEY_PLACEHOLDER => $apiKeyTransfer->getKeyOrFail(),
                ];
            }

            $this->addSuccessMessage($successMessage, $successMessageParameters);

            return $this->redirectResponse(ApiKeyGuiConfig::URL_API_KEY_LIST);
        }

        foreach ($apiKeyCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail(), $errorTransfer->getParameters());
        }

        return $this->viewResponse([
            static::KEY_FORM => $editApiKeyForm->createView(),
        ]);
    }
}
