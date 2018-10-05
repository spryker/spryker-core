<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Dependency\Plugin\CmsVersionTransferExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsUserConnector\Business\CmsUserConnectorFacadeInterface getFacade()
 */
class UserCmsVersionTransferExpanderPlugin extends AbstractPlugin implements CmsVersionTransferExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function expandTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFacade()->expandCmsVersionTransferWithUser($cmsVersionTransfer);
    }
}
