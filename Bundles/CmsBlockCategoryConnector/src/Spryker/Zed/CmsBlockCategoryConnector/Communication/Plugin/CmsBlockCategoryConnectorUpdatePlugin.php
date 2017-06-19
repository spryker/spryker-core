<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlock\Communication\Plugin\CmsBlockUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Business\CmsBlockCategoryConnectorFacadeInterface getFacade()
 */
class CmsBlockCategoryConnectorUpdatePlugin extends AbstractPlugin implements CmsBlockUpdatePluginInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFacade()
            ->updateCmsBlockCategoryRelations($cmsBlockTransfer);
    }

}