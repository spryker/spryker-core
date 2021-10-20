<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Controller;

use Spryker\Zed\CmsSlot\Business\Exception\MissingCmsSlotException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotGui\Communication\CmsSlotGuiCommunicationFactory getFactory()
 */
class ActivateSlotController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_CMS_SLOT = 'id-cms-slot';

    /**
     * @var string
     */
    protected const RESPONSE_SUCCESS_KEY = 'success';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE = 'message';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activateAction(Request $request)
    {
        $form = $this->getFactory()->createToggleActiveCmsSlotForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->jsonResponse([
                static::RESPONSE_SUCCESS_KEY => false,
                static::RESPONSE_MESSAGE => 'CSRF token is not valid.',
            ]);
        }

        $idCmsSlot = $this->castId($request->get(static::PARAM_ID_CMS_SLOT));

        try {
            $this->getFactory()->getCmsSlotFacade()->activateByIdCmsSlot($idCmsSlot);
        } catch (MissingCmsSlotException $exception) {
            return $this->jsonResponse([
                static::RESPONSE_SUCCESS_KEY => false,
                static::RESPONSE_MESSAGE => $exception->getMessage(),
            ]);
        }

        return $this->jsonResponse([static::RESPONSE_SUCCESS_KEY => true]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deactivateAction(Request $request)
    {
        $form = $this->getFactory()->createToggleActiveCmsSlotForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->jsonResponse([
                static::RESPONSE_SUCCESS_KEY => false,
                static::RESPONSE_MESSAGE => 'CSRF token is not valid.',
            ]);
        }

        $idCmsSlot = $this->castId($request->get(static::PARAM_ID_CMS_SLOT));

        try {
            $this->getFactory()->getCmsSlotFacade()->deactivateByIdCmsSlot($idCmsSlot);
        } catch (MissingCmsSlotException $exception) {
            return $this->jsonResponse([
                static::RESPONSE_SUCCESS_KEY => false,
                static::RESPONSE_MESSAGE => $exception->getMessage(),
            ]);
        }

        return $this->jsonResponse([static::RESPONSE_SUCCESS_KEY => true]);
    }
}
