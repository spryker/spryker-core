<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Communication\Plugin;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Cms\Dependency\CmsVersionPostSavePluginInterface;
use Spryker\Zed\CmsUserConnector\Business\CmsUserConnectorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method CmsUserConnectorFacadeInterface getFacade()
 */
class UserCmsVersionPostSavePlugin extends AbstractPlugin implements CmsVersionPostSavePluginInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function postSave(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFacade()->updateCmsVersion($cmsVersionTransfer);
    }
}
