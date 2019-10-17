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
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        //todo
        $idCmsSlotTemplate = 1;
        $idCmsSlot = 1;

        $slotBlockCollectionDataProvider = $this->getFactory()->createSlotBlockCollectionDataProvider();
        $slotBlockCollectionForm = $this->getFactory()
            ->createSlotBlockCollectionForm($slotBlockCollectionDataProvider, $idCmsSlotTemplate, $idCmsSlot)
            ->handleRequest($request);

        if ($slotBlockCollectionForm->isSubmitted() && $slotBlockCollectionForm->isValid()) {
            $cmsSlotBlockCollectionTransfer = $slotBlockCollectionForm->getData();

            $this->getFactory()
                ->getCmsSlotBlockFacade()
                ->saveCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
        }

        return $this->viewResponse([
            'slotName' => $this->getFactory()->getCmsSlotFacade()->findCmsSlotById($idCmsSlot)->getName(),
            'slotBlockTable' => $this->getFactory()->createSlotBlockTable()->render(),
            'slotBlockCollectionForm' => $slotBlockCollectionForm->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        return $this->jsonResponse(
            $this->getFactory()->createSlotBlockTable()->fetchData()
        );
    }
}
