<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;

interface CmsBlockGlossaryWriterInterface
{

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deleteByCmsBlockId($idCmsBlock);

    /**
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer);

}