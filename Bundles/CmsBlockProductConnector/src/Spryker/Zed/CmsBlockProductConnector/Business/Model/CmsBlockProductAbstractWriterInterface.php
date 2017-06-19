<?php


namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockProductAbstractWriterInterface
{
    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     * @return void
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer);
}