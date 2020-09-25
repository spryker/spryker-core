<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

class CmsSlotBlockGuiToCmsSlotBlockFacadeBridge implements CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Business\CmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     */
    public function __construct($cmsSlotBlockFacade)
    {
        $this->cmsSlotBlockFacade = $cmsSlotBlockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function createCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void
    {
        $this->cmsSlotBlockFacade->createCmsSlotBlockRelations($cmsSlotBlockCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return void
     */
    public function deleteCmsSlotBlockRelationsByCriteria(CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer): void
    {
        $this->cmsSlotBlockFacade->deleteCmsSlotBlockRelationsByCriteria($cmsSlotBlockCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer {
        return $this->cmsSlotBlockFacade->getCmsSlotBlockCollection($cmsSlotBlockCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(FilterTransfer $filterTransfer): array
    {
        return $this->cmsSlotBlockFacade->getCmsBlocksWithSlotRelations($filterTransfer);
    }

    /**
     * @param string $twigPath
     *
     * @return string[]
     */
    public function getTemplateConditionsByPath(string $twigPath): array
    {
        return $this->cmsSlotBlockFacade->getTemplateConditionsByPath($twigPath);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer
     */
    public function getCmsBlockPaginatedSuggestionsWithSlotRelation(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): CmsBlockSuggestionCollectionTransfer {
        return $this->cmsSlotBlockFacade
            ->getCmsBlockPaginatedSuggestionsWithSlotRelation($cmsSlotBlockCriteriaTransfer, $paginationTransfer);
    }
}
