<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockCategoryWriterInterface
{
    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

}