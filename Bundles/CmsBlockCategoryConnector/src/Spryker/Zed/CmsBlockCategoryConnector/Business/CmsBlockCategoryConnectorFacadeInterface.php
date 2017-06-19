<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Business;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockCategoryConnectorFacadeInterface
{
    /**
     * Specification
     * - delete all relations categories to cms blocks
     * - add new relations defined in the transfer object
     *
     * @api
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockCategoryRelations(CmsBlockTransfer $cmsBlockTransfer);

}