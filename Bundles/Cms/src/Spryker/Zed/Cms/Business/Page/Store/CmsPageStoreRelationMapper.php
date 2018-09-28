<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page\Store;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;

class CmsPageStoreRelationMapper implements CmsPageStoreRelationMapperInterface
{
    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapCmsPageStoreEntityCollectionToStoreRelationTransfer(SpyCmsPage $cmsPageEntity): StoreRelationTransfer
    {
        $storeTransferCollection = $this->mapStoreTransfers($cmsPageEntity);
        $idStores = $this->selectIdStores($storeTransferCollection);

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($cmsPageEntity->getIdCmsPage())
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreTransfers(SpyCmsPage $cmsPageEntity): ArrayObject
    {
        $storeTransferCollection = new ArrayObject();
        foreach ($cmsPageEntity->getSpyCmsPageStores() as $cmsPageStoreEntity) {
            $storeTransferCollection->append(
                (new StoreTransfer())
                    ->fromArray(
                        $cmsPageStoreEntity->getSpyStore()->toArray(),
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
