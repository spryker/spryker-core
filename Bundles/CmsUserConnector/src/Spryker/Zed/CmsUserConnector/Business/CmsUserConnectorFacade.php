<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function updateCmsVersionUser(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFactory()
            ->createCmsVersionUserUpdater()
            ->updateCmsVersionUser($cmsVersionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function expandCmsVersionTransferWithUser(CmsVersionTransfer $cmsVersionTransfer)
    {
        return $this->getFactory()
            ->createCmsVersionUserExpander()
            ->expandCmsVersionTransferWithUser($cmsVersionTransfer);
    }
}
