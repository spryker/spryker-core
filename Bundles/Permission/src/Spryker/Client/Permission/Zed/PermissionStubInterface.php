<?php

namespace Spryker\Client\Permission\Zed;

use Generated\Shared\Transfer\PermissionRequestTransfer;

interface PermissionStubInterface
{
    /**
     * @param PermissionRequestTransfer $requestTransfer
     *
     * @return bool
     */
    public function getIsAllowed(PermissionRequestTransfer $requestTransfer);
}