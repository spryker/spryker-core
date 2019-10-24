<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig;
use Spryker\Zed\CmsSlotBlockGui\Communication\Form\SlotBlock\CmsBlockChoiceForm;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;

class CmsBlockChoiceFormDataProvider implements CmsBlockChoiceFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     * @param \Spryker\Zed\CmsSlotBlockGui\CmsSlotBlockGuiConfig $config
     */
    public function __construct(
        CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade,
        CmsSlotBlockGuiConfig $config
    ) {
        $this->cmsSlotBlockFacade = $cmsSlotBlockFacade;
        $this->config = $config;
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return array
     */
    public function getOptions(int $idCmsSlotTemplate, int $idCmsSlot): array
    {
        $cmsBlockTransfers = $this->cmsSlotBlockFacade->getCmsBlocksWithSlotRelations(
            (new FilterTransfer())->setLimit($this->config->getMaxNumberBlocksToAssign())
        );
        $cmsBlockTransfers = $this->setCmsBlocksAssignedToSlot($cmsBlockTransfers, $idCmsSlotTemplate, $idCmsSlot);

        return [
            CmsBlockChoiceForm::OPTION_CMS_BLOCKS => $cmsBlockTransfers,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer[] $cmsBlockTransfers
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    protected function setCmsBlocksAssignedToSlot(
        array $cmsBlockTransfers,
        int $idCmsSlotTemplate,
        int $idCmsSlot
    ): array {
        foreach ($cmsBlockTransfers as $cmsBlockTransfer) {
            $cmsBlockTransfer->setIsAssignedToSlot(
                $this->isCmsBlockAssignedToSlot($cmsBlockTransfer, $idCmsSlotTemplate, $idCmsSlot)
            );
        }

        return $cmsBlockTransfers;
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
}
