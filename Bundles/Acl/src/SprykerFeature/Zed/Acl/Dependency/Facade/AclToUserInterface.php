<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Dependency\Facade;

use Generated\Shared\Transfer\UserTransfer;

interface AclToUserInterface
{

    /**
     * @param UserTransfer $user
     *
     * @return bool
     */
    public function isSystemUser(UserTransfer $user);

}
