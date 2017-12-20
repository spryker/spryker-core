<?php

namespace Spryker\Zed\CmsNavigationConnector\Communication\Plugin;

use Generated\Shared\Transfer\PageTransfer;
use Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsNavigationConnector\Business\CmsNavigationConnectorFacadeInterface getFacade()
 */
class PostCmsPageActivatorNavigationPlugin extends AbstractPlugin implements PostCmsPageActivatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     */
    public function execute(PageTransfer $pageTransfer)
    {
        $this->getFacade()->updateCmsPageNavigationNodesIsActive($pageTransfer);
    }
}
