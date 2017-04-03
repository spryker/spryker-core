<?php

namespace Spryker\Zed\CmsUserConnector\Business;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsUserConnectorFacadeInterface
{

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function updateCmsVersion(CmsVersionTransfer $cmsVersionTransfer);
}
