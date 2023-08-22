<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Controller;

use Generated\Shared\Transfer\ApiKeyCollectionRequestTransfer;
use Spryker\Zed\ApiKeyGui\ApiKeyGuiConfig;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ApiKeyGui\Communication\ApiKeyGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const KEY_FORM = 'form';

    /**
     * @var string
     */
    protected const MESSAGE_API_KEY_CREATED = 'API key `%s` created successfully. Note, it will not be shown again. Please ensure you save it securely for future use.';

    /**
     * @var string
     */
    protected const MESSAGE_KEY_PLACEHOLDER = '%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    public function indexAction(Request $request)
    {
        $createApiKeyForm = $this->getFactory()
            ->getCreateApiKeyForm()
            ->handleRequest($request);

        if ($createApiKeyForm->isSubmitted() && $createApiKeyForm->isValid()) {
            return $this->createApiKey($createApiKeyForm);
        }

        return $this->viewResponse([
            static::KEY_FORM => $createApiKeyForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createApiKeyForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<mixed>
     */
    protected function createApiKey(FormInterface $createApiKeyForm)
    {
        /** @var \Generated\Shared\Transfer\ApiKeyTransfer $apiKeyTransfer */
        $apiKeyTransfer = $createApiKeyForm->getData();
        $apiKeyTransfer->setKey($this->getFactory()->createApiKeyGenerator()->generate());

        $apiKeyCollectionResponseTransfer = $this->getFactory()
            ->getApiKeyFacade()
            ->createApiKeyCollection((new ApiKeyCollectionRequestTransfer())
                ->addApiKey($apiKeyTransfer)
                ->setIsTransactional(true));

        if ($apiKeyCollectionResponseTransfer->getErrors()->count() === 0) {
            $this->addSuccessMessage(
                static::MESSAGE_API_KEY_CREATED,
                [
                    static::MESSAGE_KEY_PLACEHOLDER => $apiKeyCollectionResponseTransfer->getApiKeys()
                        ->offsetGet(0)
                        ->getKeyOrFail(),
                ],
            );

            return $this->redirectResponse(ApiKeyGuiConfig::URL_API_KEY_LIST);
        }

        foreach ($apiKeyCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail(), $errorTransfer->getParameters());
        }

        return $this->viewResponse([
            static::KEY_FORM => $createApiKeyForm->createView(),
        ]);
    }
}
