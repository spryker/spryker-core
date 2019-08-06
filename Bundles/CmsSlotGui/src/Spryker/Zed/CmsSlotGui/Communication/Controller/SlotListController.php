<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotGui\Communication\CmsSlotGuiCommunicationFactory getFactory()
 */
class SlotListController extends AbstractController
{
    public const PARAM_ID_CMS_SLOT_TEMPLATE = 'id-cms-slot-template';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idCmsSlotTemplate = $this->castId($request->get(static::PARAM_ID_CMS_SLOT_TEMPLATE));

        return $this->jsonResponse(
            $this->getFactory()->createSlotListTable($idCmsSlotTemplate)->fetchData()
        );
    }
}
