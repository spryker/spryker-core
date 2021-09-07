<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsBlockCriteriaTransfer;
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
    /**
     * @var string
     */
    protected const PARAM_SEARCH_TEXT = 'q';
    /**
     * @var string
     */
    protected const PARAM_PAGE = 'page';
    /**
     * @var string
     */
    protected const PARAM_ID_CMS_SLOT = 'id-cms-slot';
    /**
     * @var string
     */
    protected const PARAM_ID_CMS_SLOT_TEMPLATE = 'id-cms-slot-template';
    /**
     * @var int
     */
    protected const DEFAULT_MAX_PER_PAGE = 10;
    /**
     * @var int
     */
    protected const DEFAULT_PAGE = 1;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $searchTerm = (string)$request->query->get(static::PARAM_SEARCH_TEXT);
        $page = $request->query->getInt(static::PARAM_PAGE, static::DEFAULT_PAGE);
        $idCmsSlotTemplate = $request->query->getInt(static::PARAM_ID_CMS_SLOT_TEMPLATE);
        $idCmsSlot = $request->query->getInt(static::PARAM_ID_CMS_SLOT);

        $paginationTransfer = (new PaginationTransfer())
            ->setPage($page)
            ->setMaxPerPage(static::DEFAULT_MAX_PER_PAGE);

        $cmsBlockCriteriaTransfer = (new CmsBlockCriteriaTransfer())
            ->setNamePattern($searchTerm)
            ->setPagination($paginationTransfer);

        $cmsSlotBlockCriteriaTransfer = (new CmsSlotBlockCriteriaTransfer())
            ->setIdCmsSlotTemplate($idCmsSlotTemplate)
            ->setIdCmsSlot($idCmsSlot);

        return $this->jsonResponse(
            $this->getFactory()
                ->createCmsBlockSuggestionFinder()
                ->getCmsBlockSuggestions($cmsBlockCriteriaTransfer, $cmsSlotBlockCriteriaTransfer)
        );
    }
}
