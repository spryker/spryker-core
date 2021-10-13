<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page\Store;

use ArrayObject;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;

class CmsPageStoreRelationReader implements CmsPageStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface
     */
    protected $cmsRepository;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface $cmsRepository
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsRepositoryInterface $cmsRepository
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsRepository = $cmsRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer
    {
        $storeRelationTransfer->requireIdEntity();

        $relatedStores = $this->cmsRepository->getRelatedStoresByIdCmsPage(
            $storeRelationTransfer->getIdEntity()
        );

        $idStores = $this->getIdStores($relatedStores);

        $storeRelationTransfer
            ->setStores($relatedStores)
            ->setIdStores($idStores);

        return $storeRelationTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StoreTransfer> $relatedStores
     *
     * @return array<int>
     */
    protected function getIdStores(ArrayObject $relatedStores): array
    {
        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getIdStore();
        }, $relatedStores->getArrayCopy());
    }
}
