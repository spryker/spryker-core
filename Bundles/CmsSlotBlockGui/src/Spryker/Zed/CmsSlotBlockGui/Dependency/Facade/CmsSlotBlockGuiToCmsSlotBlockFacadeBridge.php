<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsBlockCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;

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
     * @param string $twigPath
     *
     * @return string[]
     */
    public function getTemplateConditionsByPath(string $twigPath): array
    {
        return $this->cmsSlotBlockFacade->getTemplateConditionsByPath($twigPath);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockCollectionTransfer
     */
    public function getPaginatedCmsBlocksWithSlotRelations(CmsBlockCriteriaTransfer $cmsBlockCriteriaTransfer): CmsBlockCollectionTransfer
    {
        return $this->cmsSlotBlockFacade->getPaginatedCmsBlocksWithSlotRelations($cmsBlockCriteriaTransfer);
    }
}
