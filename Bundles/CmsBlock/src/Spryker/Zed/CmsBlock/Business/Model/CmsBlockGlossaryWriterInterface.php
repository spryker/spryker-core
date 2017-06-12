<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


interface CmsBlockGlossaryWriterInterface
{

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deleteByCmsBlockId($idCmsBlock);

}