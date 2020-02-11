<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockConditionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock;
use Propel\Runtime\Collection\Collection;
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
        $cmsSlotBlockEntity->setFkCmsSlot($cmsSlotBlockTransfer->getIdSlot());
        $cmsSlotBlockEntity->setFkCmsBlock($cmsSlotBlockTransfer->getIdCmsBlock());
        $cmsSlotBlockEntity->setFkCmsSlotTemplate($cmsSlotBlockTransfer->getIdSlotTemplate());
        $cmsSlotBlockEntity->setPosition($cmsSlotBlockTransfer->getPosition());

        $conditions = [];
        foreach ($cmsSlotBlockTransfer->getConditions() as $conditionKey => $cmsSlotBlockConditionTransfer) {
            $conditions[$conditionKey] = $cmsSlotBlockConditionTransfer->modifiedToArray(true, true);
        }
        $cmsSlotBlockEntity->setConditions(
            $this->utilEncodingService->encodeJson($conditions)
        );

        return $cmsSlotBlockEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $cmsSlotBlockEntities
     * @param \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function mapCmsSlotBlockEntityCollectionToTransferCollection(
        Collection $cmsSlotBlockEntities,
        CmsSlotBlockCollectionTransfer $cmsSlotBlockCollectionTransfer
    ): CmsSlotBlockCollectionTransfer {
        foreach ($cmsSlotBlockEntities as $cmsSlotBlockEntity) {
            $cmsSlotBlockCollectionTransfer->addCmsSlotBlock(
                $this->mapCmsSlotBlockEntityToTransfer($cmsSlotBlockEntity, new CmsSlotBlockTransfer())
            );
        }

        return $cmsSlotBlockCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $cmsBlockEntities
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function mapCmsBlockEntitiesToTransfers(Collection $cmsBlockEntities): array
    {
        $cmsBlockTransfers = [];
        foreach ($cmsBlockEntities as $cmsBlockEntity) {
            $cmsBlockTransfers[] = $this->mapCmsBlockEntityToTransfer($cmsBlockEntity, new CmsBlockTransfer());
        }

        return $cmsBlockTransfers;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function mapCmsBlockEntityToTransfer(
        SpyCmsBlock $cmsBlockEntity,
        CmsBlockTransfer $cmsBlockTransfer
    ): CmsBlockTransfer {
        $cmsBlockTransfer->fromArray($cmsBlockEntity->toArray(), true);
        $cmsBlockTransfer->setCmsSlotBlockCollection(
            $this->mapCmsSlotBlockEntityCollectionToTransferCollection(
                $cmsBlockEntity->getSpyCmsSlotBlocks(),
                new CmsSlotBlockCollectionTransfer()
            )
        );

        return $cmsBlockTransfer;
    }

    /**
     * @param \Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlock $cmsSlotBlockEntity
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockTransfer
     */
    protected function mapCmsSlotBlockEntityToTransfer(
        SpyCmsSlotBlock $cmsSlotBlockEntity,
        CmsSlotBlockTransfer $cmsSlotBlockTransfer
    ): CmsSlotBlockTransfer {
        $cmsSlotBlock = $cmsSlotBlockEntity->toArray();
        unset($cmsSlotBlock[CmsSlotBlockTransfer::CONDITIONS]);

        $cmsSlotBlockTransfer->fromArray($cmsSlotBlock, true);
        $cmsSlotBlockTransfer->setIdSlotTemplate($cmsSlotBlockEntity->getFkCmsSlotTemplate())
            ->setIdSlot($cmsSlotBlockEntity->getFkCmsSlot())
            ->setIdCmsBlock($cmsSlotBlockEntity->getFkCmsBlock());

        $conditions = $this->utilEncodingService->decodeJson($cmsSlotBlockEntity->getConditions(), true);
        foreach ($conditions as $conditionKey => $condition) {
            $cmsSlotBlockTransfer->addCondition(
                $conditionKey,
                (new CmsSlotBlockConditionTransfer())->fromArray($condition, true)
            );
        }

        return $cmsSlotBlockTransfer;
    }
}
