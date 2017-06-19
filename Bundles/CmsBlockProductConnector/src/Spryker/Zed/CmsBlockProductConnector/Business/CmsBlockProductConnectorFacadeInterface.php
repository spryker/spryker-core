<?php


namespace Spryker\Zed\CmsBlockProductConnector\Business;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockProductConnectorFacadeInterface
{

    /**
     * Specification
     * - delete all relations of cms block to product abstracts
     * - create relations by transfer object
     *
     * @api
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function updateCmsBlockProductAbstractRelations(CmsBlockTransfer $cmsBlockTransfer);

}