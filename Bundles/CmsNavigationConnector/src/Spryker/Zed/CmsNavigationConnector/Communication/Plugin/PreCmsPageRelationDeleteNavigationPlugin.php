<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\CmsExtension\Dependency\Plugin\PreCmsPageRelationDeletePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsNavigationConnector\Business\CmsNavigationConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsNavigationConnector\Communication\CmsNavigationConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsNavigationConnector\CmsNavigationConnectorConfig getConfig()
 */
class PreCmsPageRelationDeleteNavigationPlugin extends AbstractPlugin implements PreCmsPageRelationDeletePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return void
     */
    public function execute(CmsPageTransfer $cmsPageTransfer): void
    {
        $this->getFacade()->deleteCmsPageNavigationNodes($cmsPageTransfer);
    }
}
