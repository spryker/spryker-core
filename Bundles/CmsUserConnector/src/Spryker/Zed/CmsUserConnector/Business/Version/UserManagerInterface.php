<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\User\Persistence\SpyUser;

interface UserManagerInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function updateCmsVersion(CmsVersionTransfer $cmsVersionTransfer);

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function expandCmsVersionTransferWithUser(CmsVersionTransfer $cmsVersionTransfer);
}
