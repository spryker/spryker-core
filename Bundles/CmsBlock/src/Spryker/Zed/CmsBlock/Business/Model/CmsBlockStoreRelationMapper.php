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
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlock
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreRelationToTransfer(SpyCmsBlock $cmsBlock)
    {
        $storeTransferCollection = $this->mapStoreTransfers($cmsBlock);
        $idStores = $this->selectIdStores($storeTransferCollection);

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($cmsBlock->getIdCmsBlock())
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlock
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreTransfers(SpyCmsBlock $cmsBlock)
    {
        $storeTransferCollection = new ArrayObject();
        foreach ($cmsBlock->getSpyCmsBlockStores() as $cmsBlockStoreEntity) {
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
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeCollection
     *
     * @return int[]
     */
    protected function selectIdStores(ArrayObject $storeCollection)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeCollection->getArrayCopy());
    }
}
