<?php


namespace Spryker\Zed\CmsBlockProductConnector\Communication\Plugin;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlock\Communication\Plugin\CmsBlockUpdatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\CmsBlockProductConnector\Business\CmsBlockProductConnectorFacadeInterface;

/**
 * @method CmsBlockProductConnectorFacadeInterface getFacade()
 */
class CmsBlockProductAbstractUpdatePlugin extends AbstractPlugin implements CmsBlockUpdatePluginInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function handleUpdate(CmsBlockTransfer $cmsBlockTransfer)
    {
        $this->getFacade()
            ->updateCmsBlockProductAbstractRelations($cmsBlockTransfer);
    }

}