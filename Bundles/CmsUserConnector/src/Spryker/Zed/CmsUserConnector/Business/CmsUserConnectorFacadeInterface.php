<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsUserConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function updateCmsVersion(CmsVersionTransfer $cmsVersionTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function expandCmsVersionTransferWithUser(CmsVersionTransfer $cmsVersionTransfer);

}
