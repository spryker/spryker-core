<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\Cms\Communication\Plugin\PostCmsPageActivatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsNavigationConnector\Business\CmsNavigationConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsNavigationConnector\Communication\CmsNavigationConnectorCommunicationFactory getFactory()
 */
class PostCmsPageActivatorNavigationPlugin extends AbstractPlugin implements PostCmsPageActivatorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    public function execute(CmsPageTransfer $cmsPageTransfer)
    {
        $this->getFacade()->updateCmsPageNavigationNodesIsActive($cmsPageTransfer);
    }
}
