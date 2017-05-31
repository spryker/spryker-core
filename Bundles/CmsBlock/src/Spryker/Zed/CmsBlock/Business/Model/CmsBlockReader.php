<?php

namespace Spryker\Zed\CmsBlock\Business\Model;

use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockReader implements CmsBlockReaderInterface
{
    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CmsBlockMapperInterface
     */
    protected $mapper;

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
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function findCmsBlockById($idCmsBlock)
    {
        $spyCmsBlock = $this->queryContainer
            ->queryCmsBlockById($idCmsBlock)
            ->findOne();

        $cmsBlockTransfer = $this->mapper->mapCmsBlockEntityToTransfer($spyCmsBlock);

        return $cmsBlockTransfer;
    }


}