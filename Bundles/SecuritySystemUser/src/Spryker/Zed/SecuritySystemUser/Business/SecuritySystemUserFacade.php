<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SecuritySystemUser\Business\SecuritySystemUserBusinessFactory getFactory()
 */
class SecuritySystemUserFacade extends AbstractFacade implements SecuritySystemUserFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isAclEntityDisabled(): bool
    {
        return $this->getFactory()->createSecuritySystemUserReader()->isCurrentUserSystem();
    }
}
