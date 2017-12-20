<?php

namespace Spryker\Client\Rbac\Zed;


use Generated\Shared\Transfer\RbacRequestTransfer;
use Spryker\Client\Rbac\RbacClientInterface;

class RbacStab implements RbacStabInterface
{
    public function getIsAllowed(RbacRequestTransfer $requestTransfer)
    {
        return true;
    }
}