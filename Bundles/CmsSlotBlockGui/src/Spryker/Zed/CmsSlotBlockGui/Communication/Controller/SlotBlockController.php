<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiFacadeInterface getFacade()
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

        $cmsBlockChoiceForm = $this->getFactory()->createCmsBlockChoiceForm();
        $cmsSlotBlockCollectionForm = $this->getSlotBlockCollectionForm($request, $idCmsSlotTemplate, $idCmsSlot);

        return $this->viewResponse([
            'slotName' => $this->getFactory()->getCmsSlotFacade()->getCmsSlotById($idCmsSlot)->getName(),
            'slotBlockTable' => $this->getFactory()->createSlotBlockTable($idCmsSlotTemplate, $idCmsSlot)->render(),
            'cmsBlockChoiceForm' => $cmsBlockChoiceForm->createView(),
            'cmsSlotBlockCollectionForm' => $cmsSlotBlockCollectionForm->createView(),
            'idCmsSlotTemplate' => $idCmsSlotTemplate,
            'idCmsSlot' => $idCmsSlot,
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function formAction(Request $request): array
    {
        $idCmsSlotTemplate = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT_TEMPLATE));
        $idCmsSlot = $this->castId($request->query->get(static::PARAM_ID_CMS_SLOT));
        $cmsSlotBlockCollectionForm = $this->getSlotBlockCollectionForm($request, $idCmsSlotTemplate, $idCmsSlot);

        return $this->viewResponse([
            'cmsSlotBlockCollectionForm' => $cmsSlotBlockCollectionForm->createView(),
            'idCmsSlotTemplate' => $idCmsSlotTemplate,
            'idCmsSlot' => $idCmsSlot,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getSlotBlockCollectionForm(Request $request, int $idCmsSlotTemplate, int $idCmsSlot): FormInterface
    {
        $cmsSlotBlockCollectionForm = $this->getFactory()
            ->createCmsSlotBlockCollectionForm($idCmsSlotTemplate, $idCmsSlot)
            ->handleRequest($request);

        if ($cmsSlotBlockCollectionForm->isSubmitted() && $cmsSlotBlockCollectionForm->isValid()) {
            $cmsSlotBlockFacade = $this->getFactory()->getCmsSlotBlockFacade();

            $cmsSlotBlockCriteriaTransfer = (new CmsSlotBlockCriteriaTransfer())
                ->setIdCmsSlotTemplate($idCmsSlotTemplate)
                ->setIdCmsSlot($idCmsSlot);
            $cmsSlotBlockCollectionTransfer = $cmsSlotBlockCollectionForm->getData();

            $cmsSlotBlockFacade->deleteCmsSlotBlockRelationsByCriteria($cmsSlotBlockCriteriaTransfer);
            $cmsSlotBlockFacade->createCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
        }

        return $cmsSlotBlockCollectionForm;
    }
}
