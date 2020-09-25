<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business;

use Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;

interface CmsSlotBlockFacadeInterface
{
    /**
     * Specification:
     * - Creates relations between CMS Slots and CMS Blocks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return void
     */
    public function createCmsSlotBlockRelations(CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer): void;

    /**
     * Specification:
     * - Removes relations between CMS Slots and CMS blocks by CmsSlotBlockCriteriaTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return void
     */
    public function deleteCmsSlotBlockRelationsByCriteria(CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer): void;

    /**
     * Specification:
     * - Retrieves collection of CmsSlotBlockTransfers according provided criteria.
     * - Filters by idCmsSlotTemplate if provided via criteria transfer.
     * - Filters by idCmsSlot if provided via criteria transfer.
     * - Applies FilterTransfer provided by CmsSlotBlockCriteriaTransfer::getFilter().
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer;

    /**
     * Specification:
     * - Returns CMS Block transfers with CMS Slot relations according to given offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksWithSlotRelations(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Returns configuration of conditions for template by provided path.
     *
     * @api
     *
     * @param string $twigPath
     *
     * @return string[]
     */
    public function getTemplateConditionsByPath(string $twigPath): array;

    /**
     * Specification:
     * - Searches CMS Blocks suggestions based on CMS Block name.
     * - Paginates search results according to pagination.
     * - Returns a collection of suggested CMS block transfers with CMS Slot relations and pagination.
     * - PaginationTransfer.Page and PaginationTransfer.MaxPerPage must be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockSuggestionCollectionTransfer
     */
    public function getCmsBlockPaginatedSuggestionsWithSlotRelation(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): CmsBlockSuggestionCollectionTransfer;
}
