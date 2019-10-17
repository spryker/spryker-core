<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Spryker\Zed\CmsSlotBlock\Dependency\Service\CmsSlotBlockToUtilEncodingServiceInterface;

class CmsSlotBlockMapper implements CmsSlotBlockMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Dependency\Service\CmsSlotBlockToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Dependency\Service\CmsSlotBlockToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(CmsSlotBlockToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock $cmsSlotBlockEntity
     *
     * @return \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock
     */
    public function mapCmsSlotBlockTransferToEntity(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        SpyCmsSlotBlock $cmsSlotBlockEntity
    ): SpyCmsSlotBlock {
        $cmsSlotBlockEntity->fromArray($cmsSlotBlockTransfer->toArray());
        $cmsSlotBlockEntity->setFkCmsSlot($cmsSlotBlockTransfer->getIdSlot());
        $cmsSlotBlockEntity->setFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock());
        $cmsSlotBlockEntity->setFkCmsSlotTemplate($cmsSlotBlockTransfer->getIdSlotTemplate());

        $cmsSlotBlockEntity->setConditions(
            $this->utilEncodingService->encodeJson($cmsSlotBlockTransfer->getConditions())
        );

        return $cmsSlotBlockEntity;
    }
}
