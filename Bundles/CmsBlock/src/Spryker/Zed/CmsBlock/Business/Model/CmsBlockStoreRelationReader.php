<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockStoreRelationReader implements CmsBlockStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct(CmsBlockQueryContainerInterface $cmsBlockQueryContainer)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $storeTransferCollection = $this->getRelatedStores($storeRelationTransfer->getIdEntity());
        $idStores = $this->getIdStores($storeTransferCollection);

        $storeRelationTransfer
            ->setStores($storeTransferCollection)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function getRelatedStores($idCmsBlock)
    {
        $cmsBlockStoreCollection = $this->cmsBlockQueryContainer
            ->queryCmsBlockStoreWithStoresByFkCmsBlock($idCmsBlock)
            ->find();

        $relatedStores = new ArrayObject();
        foreach ($cmsBlockStoreCollection as $cmsBlockStoreEntity) {
            $relatedStores->append(
                (new StoreTransfer())
                    ->fromArray(
                        $cmsBlockStoreEntity->getSpyStore()->toArray(),
                        true
                    )
            );
        }

        return $relatedStores;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $storeTransferCollection
     *
     * @return int[]
     */
    protected function getIdStores(ArrayObject $storeTransferCollection)
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $storeTransferCollection->getArrayCopy());
    }
}
