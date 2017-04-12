<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsUserConnector\Business\CmsUserConnectorBusinessFactory getFactory()
 */
class CmsUserConnectorFacade extends AbstractFacade implements CmsUserConnectorFacadeInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function updateCmsVersion(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFactory()
            ->createUserManager()
            ->updateCmsVersion($cmsVersionTransfer);
    }
}
