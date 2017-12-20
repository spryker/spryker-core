<?php

namespace Spryker\Client\Rbac\Zed;

use Generated\Shared\Transfer\RbacRequestTransfer;

interface RbacStabInterface
{
    /**
     * @param RbacRequestTransfer $requestTransfer
     *
     * @return bool
     */
    public function getIsAllowed(RbacRequestTransfer $requestTransfer);
}