<?php


namespace Spryker\Zed\CmsGui\Dependency\Facade;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsGuiToCmsBlockInterface
{

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockTransfer|null
     */
    public function findCmsBlockId($idCmsBlock);

}