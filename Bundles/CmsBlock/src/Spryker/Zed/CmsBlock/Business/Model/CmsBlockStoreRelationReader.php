<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockStoreRelationReader implements CmsBlockStoreRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface
     */
    protected $cmsBlockStoreRelationMapper;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface $cmsBlockStoreRelationMapper
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockStoreRelationMapperInterface $cmsBlockStoreRelationMapper
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockStoreRelationMapper = $cmsBlockStoreRelationMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $storeRelationTransfer->requireIdEntity();

        $cmsBlockEntity = $this->cmsBlockQueryContainer
            ->queryCmsBlockWithStoreRelationByFkCmsBlock($storeRelationTransfer->getIdEntity())
            ->find()
            ->getFirst();

        $storeRelationTransfer = $this->cmsBlockStoreRelationMapper->mapStoreRelationToTransfer($cmsBlockEntity);

        return $storeRelationTransfer;
    }
}
