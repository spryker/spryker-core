<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Propel\Runtime\Collection\Collection;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapperInterface
     */
    protected $cmsSlotBlockMapper;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper\CmsSlotBlockMapperInterface $cmsSlotBlockMapper
     */
    public function __construct(CmsSlotBlockMapperInterface $cmsSlotBlockMapper)
    {
        $this->cmsSlotBlockMapper = $cmsSlotBlockMapper;
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
        $cmsBlockTransfer->setStoreRelation(
            $this->mapStoreRelationToTransfer($cmsBlockEntity)
        );
        $cmsBlockTransfer->setCmsSlotBlockCollection(
            $this->cmsSlotBlockMapper->mapCmsSlotBlockEntityCollectionToTransferCollection(
                $cmsBlockEntity->getSpyCmsSlotBlocks(),
                new CmsSlotBlockCollectionTransfer()
            )
        );

        return $cmsBlockTransfer;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreRelationToTransfer(SpyCmsBlock $cmsBlockEntity): StoreRelationTransfer
    {
        $storeTransferCollection = $this->mapStoreTransfers($cmsBlockEntity);
        $idStores = $this->selectIdStores($storeTransferCollection);

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($cmsBlockEntity->getIdCmsBlock())
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreTransfers(SpyCmsBlock $cmsBlockEntity): ArrayObject
    {
        $storeTransferCollection = new ArrayObject();
        foreach ($cmsBlockEntity->getSpyCmsBlockStores() as $cmsBlockStoreEntity) {
            $storeTransferCollection->append(
                (new StoreTransfer())
                    ->fromArray(
                        $cmsBlockStoreEntity->getSpyStore()->toArray(),
                        true
                    )
            );
        }

        return $storeTransferCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransferCollection
     *
     * @return int[]
     */
    protected function selectIdStores(ArrayObject $storeTransferCollection): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
