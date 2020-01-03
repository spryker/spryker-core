<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockReader implements CmsBlockReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface
     */
    protected $mapper;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockMapperInterface $cmsBlockMapper
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockMapperInterface $cmsBlockMapper
    ) {
        $this->queryContainer = $cmsBlockQueryContainer;
        $this->mapper = $cmsBlockMapper;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById(int $idCmsBlock): ?CmsBlockTransfer
    {
        $spyCmsBlock = $this->queryContainer
            ->queryCmsBlockByIdWithTemplateWithGlossaryWithStoreRelation($idCmsBlock)
            ->find()
            ->getFirst();

        if (!$spyCmsBlock) {
            return null;
        }

        return $this->mapper->mapCmsBlockEntityToTransfer($spyCmsBlock);
    }
}
