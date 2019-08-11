<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotGui\Communication\CmsSlotGuiCommunicationFactory getFactory()
 */
class ActivateSlotController extends AbstractController
{
    public const PARAM_ID_CMS_SLOT = 'id-cms-slot';
    protected const RESPONSE_SUCCESS_KEY = 'success';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activateAction(Request $request)
    {
        $idCmsSlot = $this->castId($request->get(static::PARAM_ID_CMS_SLOT));

        $this->getFactory()->getCmsSlotFacade()->activateByIdCmsSlot($idCmsSlot);

        return $this->jsonResponse([static::RESPONSE_SUCCESS_KEY => true]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deactivateAction(Request $request)
    {
        $idCmsSlot = $this->castId($request->get(static::PARAM_ID_CMS_SLOT));

        $this->getFactory()->getCmsSlotFacade()->deactivateByIdCmsSlot($idCmsSlot);

        return $this->jsonResponse(['success' => true]);
    }
}
