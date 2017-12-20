<?php

namespace Spryker\Zed\CmsNavigationConnector\Business;

use Generated\Shared\Transfer\PageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsNavigationConnector\Business\CmsNavigationConnectorBusinessFactory getFactory()
 */
class CmsNavigationConnectorFacade extends AbstractFacade implements CmsNavigationConnectorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     */
    public function updateCmsPageNavigationNodesIsActive(PageTransfer $pageTransfer)
    {
        $this->getFactory()
            ->createNavigationNodesIsActiveUpdater()
            ->updateCmsPageNavigationNodes($pageTransfer->getIdCmsPage(), $pageTransfer->getIsActive());
    }
}
