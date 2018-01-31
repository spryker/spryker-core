<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;

class CmsBlockStoreRelationMapper implements CmsBlockStoreRelationMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreRelationToTransfer(SpyCmsBlock $cmsBlockEntity)
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
    protected function mapStoreTransfers(SpyCmsBlock $cmsBlockEntity)
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
    protected function selectIdStores(ArrayObject $storeTransferCollection)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
