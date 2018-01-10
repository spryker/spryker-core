<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStore;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockStoreRelationWriter implements CmsBlockStoreRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface
     */
    protected $cmsBlockStoreRelationReader;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface $cmsBlockStoreRelationReader
     */
    public function __construct(CmsBlockQueryContainerInterface $cmsBlockQueryContainer, CmsBlockStoreRelationReaderInterface $cmsBlockStoreRelationReader)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockStoreRelationReader = $cmsBlockStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer)
    {
        $currentIdStores = $this->getIdStores($storeRelationTransfer->getIdEntity());

        $saveIdStores = array_diff($storeRelationTransfer->getIdStores(), $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $storeRelationTransfer->getIdStores());

        $this->addStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->removeStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int[] $idStores
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function addStores(array $idStores, $idCmsBlock)
    {
        foreach ($idStores as $idStore) {
            (new SpyCmsBlockStore())
                ->setFkStore($idStore)
                ->setFkCmsBlock($idCmsBlock)
                ->save();
        }
    }

    /**
     * @param int[] $idStores
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function removeStores(array $idStores, $idCmsBlock)
    {
        if (count($idStores) === 0) {
            return;
        }

        $this->cmsBlockQueryContainer
            ->queryCmsBlockStoreByFkCmsBlockAndFkStores($idCmsBlock, $idStores)
            ->delete();
    }

    /**
     * @param int $idCmsBlock
     *
     * @return int[]
     */
    protected function getIdStores($idCmsBlock)
    {
        $storeRelation = $this->cmsBlockStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idCmsBlock)
        );

        return $storeRelation->getIdStores();
    }
}
