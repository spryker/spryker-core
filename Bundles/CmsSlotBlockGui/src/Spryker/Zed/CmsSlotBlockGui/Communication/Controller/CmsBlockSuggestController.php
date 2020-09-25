<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsSlotBlockGui\Business\CmsSlotBlockGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsSlotBlockGui\Communication\CmsSlotBlockGuiCommunicationFactory getFactory()
 */
class CmsBlockSuggestController extends AbstractController
{
    protected const PARAM_SEARCH_TEXT = 'q';
    protected const PARAM_PAGE = 'page';
    protected const PARAM_ID_CMS_SLOT = 'id-cms-slot';
    protected const PARAM_ID_CMS_SLOT_TEMPLATE = 'id-cms-slot-template';
    protected const DEFAULT_MAX_PER_PAGE = 10;
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $requestQuery = $request->query;

        $searchTerm = (string)$requestQuery->get(static::PARAM_SEARCH_TEXT);
        $page = (int)$requestQuery->get(static::PARAM_PAGE, static::DEFAULT_PAGE);
        $idCmsSlotTemplate = (int)$requestQuery->get(static::PARAM_ID_CMS_SLOT_TEMPLATE);
        $idCmsSlot = (int)$requestQuery->get(static::PARAM_ID_CMS_SLOT);

        $cmsSlotBlockCriteriaTransfer = $this->buildCmsSlotBlockCriteriaTransfer($searchTerm, $idCmsSlotTemplate, $idCmsSlot);
        $paginationTransfer = $this->buildPaginationTransfer($page);

        return $this->jsonResponse(
            $this->getFacade()
                ->getCmsBlockSuggestions($cmsSlotBlockCriteriaTransfer, $paginationTransfer)
        );
    }

    /**
     * @param string $searchTerm
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer
     */
    protected function buildCmsSlotBlockCriteriaTransfer(
        string $searchTerm,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): CmsSlotBlockCriteriaTransfer {
        return (new CmsSlotBlockCriteriaTransfer())
            ->setCmsBlockName($searchTerm)
            ->setIdCmsSlotTemplate($idCmsSlotTemplate)
            ->setIdCmsSlot($idCmsSlot);
    }

    /**
     * @param int $page
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function buildPaginationTransfer(int $page): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(static::DEFAULT_MAX_PER_PAGE);
    }
}
