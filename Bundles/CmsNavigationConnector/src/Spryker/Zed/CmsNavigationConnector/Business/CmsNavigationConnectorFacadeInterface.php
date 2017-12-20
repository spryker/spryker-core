<?php


namespace Spryker\Zed\CmsNavigationConnector\Business;


use Generated\Shared\Transfer\PageTransfer;

interface CmsNavigationConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     */
    public function updateCmsPageNavigationNodesIsActive(PageTransfer $pageTransfer);
}