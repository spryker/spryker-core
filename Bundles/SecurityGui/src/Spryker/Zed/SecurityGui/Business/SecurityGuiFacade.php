<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGui\Business;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SecurityGui\Business\SecurityGuiBusinessFactory getFactory()
 */
class SecurityGuiFacade extends AbstractFacade implements SecurityGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    public function authorizeUser(UserTransfer $userTransfer): void
    {
        $this->getFactory()
            ->createUserAuthorizer()
            ->authorizeUser($userTransfer);
    }
}
