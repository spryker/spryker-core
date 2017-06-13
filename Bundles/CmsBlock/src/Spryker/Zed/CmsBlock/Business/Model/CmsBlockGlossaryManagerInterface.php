<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;

interface CmsBlockGlossaryManagerInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function findPlaceholders($idCmsBlock);

}