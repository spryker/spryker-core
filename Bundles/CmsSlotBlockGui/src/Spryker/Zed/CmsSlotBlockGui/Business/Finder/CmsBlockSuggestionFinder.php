<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Business\Finder;

use ArrayObject;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;

class CmsBlockSuggestionFinder implements CmsBlockSuggestionFinderInterface
{
    protected const RESPONSE_KEY_RESULTS = 'results';
    protected const RESPONSE_KEY_PAGINATION = 'pagination';
    protected const RESPONSE_KEY_PAGINATION_MORE = 'more';

    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    protected $slotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $slotBlockFacade
     */
    public function __construct(CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $slotBlockFacade)
    {
        $this->slotBlockFacade = $slotBlockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    public function getCmsBlockSuggestions(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer,
        PaginationTransfer $paginationTransfer
    ): array {
        $cmsBlockSuggestionCollectionTransfer = $this
            ->slotBlockFacade
            ->getCmsBlockPaginatedSuggestionsWithSlotRelation($cmsSlotBlockCriteriaTransfer, $paginationTransfer);

        return [
            static::RESPONSE_KEY_RESULTS => $this->transformCmsBlocksToSuggestionData(
                $cmsBlockSuggestionCollectionTransfer->getCmsBlocks(),
                $cmsSlotBlockCriteriaTransfer
            ),
            static::RESPONSE_KEY_PAGINATION => $this->getPaginationData(
                $cmsBlockSuggestionCollectionTransfer->getPagination()
            ),
        ];
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return array
     */
    protected function transformCmsBlocksToSuggestionData(
        ArrayObject $cmsBlockTransfers,
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): array {
        $idCmsSlotTemplate = $cmsSlotBlockCriteriaTransfer->getIdCmsSlotTemplate();
        $idCmsSlot = $cmsSlotBlockCriteriaTransfer->getIdCmsSlot();

        $data = [];
        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $data[] = [
                'id' => $cmsBlockTransfer->getIdCmsBlock(),
                'text' => $cmsBlockTransfer->getName(),
                'isActive' => $cmsBlockTransfer->getIsActive(),
                'validFrom' => $this->formatValidityDateTime($cmsBlockTransfer->getValidFrom()),
                'validTo' => $this->formatValidityDateTime($cmsBlockTransfer->getValidTo()),
                'stores' => $cmsBlockTransfer->getStoreNames(),
                'disabled' => $this->isCmsBlockAssignedToSlotAndTemplate($cmsBlockTransfer, $idCmsSlotTemplate, $idCmsSlot),
            ];
        }

        return $data;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return bool
     */
    protected function isCmsBlockAssignedToSlotAndTemplate(
        CmsBlockTransfer $cmsBlockTransfer,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): bool {
        $cmsSlotBlocks = $cmsBlockTransfer->getCmsSlotBlockCollection()->getCmsSlotBlocks();
        foreach ($cmsSlotBlocks as $cmsSlotBlock) {
            if ($cmsSlotBlock->getIdSlotTemplate() === $idCmsSlotTemplate && $cmsSlotBlock->getIdSlot() === $idCmsSlot) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|null $dateTime
     *
     * @return string
     */
    protected function formatValidityDateTime(?string $dateTime): string
    {
        return $dateTime
            ? date('F d, Y H:i', strtotime($dateTime))
            : '-';
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return array
     */
    protected function getPaginationData(PaginationTransfer $paginationTransfer): array
    {
        $hasMorePages = $paginationTransfer->getLastPage() > 0 &&
            $paginationTransfer->getLastPage() !== $paginationTransfer->getPage();

        return [
            static::RESPONSE_KEY_PAGINATION_MORE => $hasMorePages,
        ];
    }
}
