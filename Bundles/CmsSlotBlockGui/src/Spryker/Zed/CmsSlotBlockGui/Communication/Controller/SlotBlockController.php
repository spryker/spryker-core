<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 */
class SlotBlockController extends AbstractController
{
    protected const PARAM_ID_CMS_SLOT_TEMPLATE = 'id-cms-slot-template';
    protected const PARAM_ID_CMS_SLOT = 'id-cms-slot';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $idCmsSlotTemplate = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT_TEMPLATE));
        $idCmsSlot = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT));

        $slotBlockCollectionDataProvider = $this->getFactory()->createCmsSlotBlockCollectionFormDataProvider();
        $cmsSlotBlockCollectionForm = $this->getFactory()
            ->createCmsSlotBlockCollectionForm($slotBlockCollectionDataProvider, $idCmsSlotTemplate, $idCmsSlot)
            ->handleRequest($request);

        if ($cmsSlotBlockCollectionForm->isSubmitted() && $cmsSlotBlockCollectionForm->isValid()) {
            $cmsSlotBlockCollectionTransfer = $cmsSlotBlockCollectionForm->getData();

            $this->getFactory()
                ->getCmsSlotBlockFacade()
                ->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
        }

        return $this->viewResponse([
            'slotName' => $this->getFactory()->getCmsSlotFacade()->findCmsSlotById($idCmsSlot)->getName(),
            'slotBlockTable' => $this->getFactory()->createSlotBlockTable($idCmsSlotTemplate, $idCmsSlot)->render(),
            'cmsSlotBlockCollectionForm' => $cmsSlotBlockCollectionForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $idCmsSlotTemplate = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT_TEMPLATE));
        $idCmsSlot = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT));

        return $this->jsonResponse(
            $this->getFactory()->createSlotBlockTable($idCmsSlotTemplate, $idCmsSlot)->fetchData()
        );
    }
}
