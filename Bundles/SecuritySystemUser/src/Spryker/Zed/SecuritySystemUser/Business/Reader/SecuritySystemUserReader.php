<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Business\Reader;

use Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface;

class SecuritySystemUserReader implements SecuritySystemUserReaderInterface
{
    /**
     * @var \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface $userFacade
     */
    public function __construct(SecuritySystemUserToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @return bool
     */
    public function isCurrentUserSystem(): bool
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return false;
        }

        return (bool)$this->userFacade->getCurrentUser()->getIsSystemUser();
    }
}
