<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Reader;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface;

class CmsSlotBlockReader implements CmsSlotBlockReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface
     */
    protected $cmsSlotBlockRepository;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository
     */
    public function __construct(CmsSlotBlockRepositoryInterface $cmsSlotBlockRepository)
    {
        $this->cmsSlotBlockRepository = $cmsSlotBlockRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getCmsSlotBlockCollection(
        CmsSlotBlockCriteriaTransfer $cmsSlotBlockCriteriaTransfer
    ): CmsSlotBlockCollectionTransfer {
        return $this->cmsSlotBlockRepository->getCmsSlotBlocks($cmsSlotBlockCriteriaTransfer);
    }
}
