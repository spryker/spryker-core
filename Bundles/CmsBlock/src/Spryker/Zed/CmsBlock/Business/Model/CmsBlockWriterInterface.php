<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;

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

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

}