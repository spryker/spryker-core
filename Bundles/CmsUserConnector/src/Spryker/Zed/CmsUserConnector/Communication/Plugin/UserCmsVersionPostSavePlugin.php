<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Dependency\Plugin\CmsVersionPostSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CmsUserConnector\Business\CmsUserConnectorFacadeInterface getFacade()
 */
class UserCmsVersionPostSavePlugin extends AbstractPlugin implements CmsVersionPostSavePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function postSave(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFacade()->updateCmsVersionUser($cmsVersionTransfer);
    }
}
