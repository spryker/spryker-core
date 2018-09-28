<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page\Store;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPageStore;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageStoreRelationWriter implements CmsPageStoreRelationWriterInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationReaderInterface
     */
    protected $cmsPageStoreRelationReader;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationReaderInterface $cmsPageStoreRelationReader
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, CmsPageStoreRelationReaderInterface $cmsPageStoreRelationReader)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageStoreRelationReader = $cmsPageStoreRelationReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    public function update(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireIdEntity();

        $currentIdStores = $this->getIdStoresByIdCmsPage($storeRelationTransfer->getIdEntity());
        $requestedIdStores = $storeRelationTransfer->getIdStores() ?? [];

        $saveIdStores = array_diff($requestedIdStores, $currentIdStores);
        $deleteIdStores = array_diff($currentIdStores, $requestedIdStores);

        $this->addStoreRelations($saveIdStores, $storeRelationTransfer->getIdEntity());
        $this->removeStoreRelations($deleteIdStores, $storeRelationTransfer->getIdEntity());
    }

    /**
     * @param int[] $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function addStoreRelations(array $idStores, int $idCmsPage): void
    {
        foreach ($idStores as $idStore) {
            (new SpyCmsPageStore())
                ->setFkStore($idStore)
                ->setFkCmsPage($idCmsPage)
                ->save();
        }
    }

    /**
     * @param array $idStores
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function removeStoreRelations(array $idStores, int $idCmsPage): void
    {
        if (empty($idStores)) {
            return;
        }

        $cmsPageStoreEntities = $this->cmsQueryContainer
            ->queryCmsPageStoreByFkCmsPageAndFkStores($idCmsPage, $idStores)
            ->find();

        foreach ($cmsPageStoreEntities as $cmsPageStoreEntity) {
            $cmsPageStoreEntity->delete();
        }
    }

    /**
     * @param int $idCmsPage
     *
     * @return int[]
     */
    protected function getIdStoresByIdCmsPage(int $idCmsPage): array
    {
        $storeRelation = $this->cmsPageStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())->setIdEntity($idCmsPage)
        );

        return $storeRelation->getIdStores();
    }
}
