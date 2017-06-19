<?php

namespace Spryker\Zed\CmsBlock\Communication\Plugin;


use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockUpdatePluginInterface
{
    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer);

}