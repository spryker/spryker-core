<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Dependency\CmsVersionPostSavePluginInterface;
use Spryker\Zed\Cms\Dependency\CmsVersionTransferExpanderPlugin;
use Spryker\Zed\CmsUserConnector\Business\CmsUserConnectorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CmsUserConnectorFacadeInterface getFacade()
 */
class UserCmsVersionTransferExpanderPlugin extends AbstractPlugin implements CmsVersionTransferExpanderPlugin
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function expandTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFacade()->expandCmsVersionTransferWithUser($cmsVersionTransfer);
    }
}
