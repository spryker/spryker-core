<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Security\Business\SecurityBusinessFactory getFactory()
 */
class SecurityFacade extends AbstractFacade implements SecurityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        return $this->getFactory()
                ->createSecurityAuthorizationChecker()
               ->isUserLoggedIn();
    }
}
