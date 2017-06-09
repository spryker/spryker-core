<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


interface CmsBlockWriterInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock);

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock);
}