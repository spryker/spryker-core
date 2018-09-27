<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page\Store;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageStoreRelationReader implements CmsPageStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /***
     * @var \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapperInterface
     */
    protected $cmsPageStoreRelationMapper;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\Store\CmsPageStoreRelationMapperInterface $cmsPageStoreRelationMapper
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsPageStoreRelationMapperInterface $cmsPageStoreRelationMapper
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageStoreRelationMapper = $cmsPageStoreRelationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer): StoreRelationTransfer
    {
        $storeRelationTransfer->requireIdEntity();

        $cmsPageEntity = $this->cmsQueryContainer
            ->queryCmsPageWithStoreRelationByFkCmsPage($storeRelationTransfer->getIdEntity())
            ->find()
            ->getFirst();

        $storeRelationTransfer = $this->cmsPageStoreRelationMapper->mapStoreRelationToTransfer($cmsPageEntity);

        return $storeRelationTransfer;
    }
}
