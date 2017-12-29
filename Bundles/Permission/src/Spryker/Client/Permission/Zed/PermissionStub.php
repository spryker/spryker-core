<?php


namespace Spryker\Client\Permission\Zed;


use Generated\Shared\Transfer\PermissionRequestTransfer;

class PermissionStub implements PermissionStubInterface
{
    public function getIsAllowed(PermissionRequestTransfer $requestTransfer)
    {
        return false;
    }
}