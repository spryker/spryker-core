<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock\CmsBlockChoiceForm;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;

class CmsBlockChoiceFormDataProvider implements CmsBlockChoiceFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     */
    public function __construct(CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade)
    {
        $this->cmsSlotBlockFacade = $cmsSlotBlockFacade;
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return array
     */
    public function getOptions(int $idCmsSlotTemplate, int $idCmsSlot): array
    {
        $cmsBlockTransfers = $this->cmsSlotBlockFacade->getCmsBlocksWithSlotRelations();

        return [
            CmsBlockChoiceForm::OPTION_CMS_BLOCKS => $cmsBlockTransfers,
            CmsBlockChoiceForm::OPTION_CMS_BLOCKS_STORES => $this->getStoreNames($cmsBlockTransfers),
            CmsBlockChoiceForm::OPTION_CMS_BLOCK_IDS_ASSIGNED_TO_SLOT => $this->getCmsBlockIdsAssignedToSlot(
                $cmsBlockTransfers,
                $idCmsSlotTemplate,
                $idCmsSlot
            ),

        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return int[]
     */
    protected function getCmsBlockIdsAssignedToSlot(
        array $cmsBlockTransfers,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): array {
        $cmsBlockIds = [];
        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            if ($this->isCmsBlockAssignedToSlot($cmsBlockTransfer, $idCmsSlotTemplate, $idCmsSlot)) {
                $cmsBlockIds[$cmsBlockTransfer->getIdCmsBlock()] = $cmsBlockTransfer->getIdCmsBlock();
            }
        }

        return $cmsBlockIds;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return bool
     */
    protected function isCmsBlockAssignedToSlot(
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
     * @param \Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     *
     * @return string[]
     */
    protected function getStoreNames(array $cmsBlockTransfers): array
    {
        $storeNames = [];
        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $storeNames[$cmsBlockTransfer->getIdCmsBlock()] = $this->getCmsBlockStoreNames($cmsBlockTransfer);
        }

        return $storeNames;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return string
     */
    protected function getCmsBlockStoreNames(CmsBlockTransfer $cmsBlockTransfer): string
    {
        $storeTransfers = $cmsBlockTransfer->getStoreRelation()->getStores()->getArrayCopy();

        return implode(',', array_map(function (StoreTransfer $storeTransfer): string {
            return $storeTransfer->getName();
        }, $storeTransfers));
    }
}
