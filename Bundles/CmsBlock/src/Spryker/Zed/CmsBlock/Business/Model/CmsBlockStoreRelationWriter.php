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
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockStoreRelationReaderInterface $cmsBlockStoreRelationReader
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockStoreRelationReader = $cmsBlockStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdCmsBlock($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $this->findStoreRelationIdStores($storeRelationTransfer);

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->addStores($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->removeStores($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param array<int> $idStores
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function addStores(array $idStores, int $idCmsBlock): void
    {
        foreach ($idStores as $idStore) {
            (new SpyCmsBlockStore())
                ->setFkStore($idStore)
                ->setFkCmsBlock($idCmsBlock)
                ->save();
        }
    }

    /**
     * @param array<int> $idStores
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function removeStores(array $idStores, int $idCmsBlock): void
    {
        if (count($idStores) === 0) {
            return;
        }

        $cmsBlockStoreEntities = $this->cmsBlockQueryContainer
            ->queryCmsBlockStoreByFkCmsBlockAndFkStores($idCmsBlock, $idStores)
            ->find();

        foreach ($cmsBlockStoreEntities as $cmsBlockStoreEntity) {
            $cmsBlockStoreEntity->delete();
        }
    }

    /**
     * @param int $idCmsBlock
     *
     * @return array<int>
     */
    protected function getIdStoresByIdCmsBlock(int $idCmsBlock): array
    {
        $storeRelation = $this->cmsBlockStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idCmsBlock),
        );

        return $storeRelation->getIdStores();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return array<int>
     */
    protected function findStoreRelationIdStores(StoreRelationTransfer $storeRelationTransfer): array
    {
        return $storeRelationTransfer->getIdStores();
    }
}
