<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityOauthUser\Communication\Security;

use Generated\Shared\Transfer\UserTransfer;
use Symfony\Component\Security\Core\User\UserInterface;

interface SecurityOauthUserInterface extends UserInterface
{
    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getUserTransfer(): UserTransfer;
}
