<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SecuritySystemUser\Business\Reader\SecuritySystemUserReader;
use Spryker\Zed\SecuritySystemUser\Business\Reader\SecuritySystemUserReaderInterface;
use Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface;
use Spryker\Zed\SecuritySystemUser\SecuritySystemUserDependencyProvider;

/**
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
 * @method \Spryker\Zed\SecuritySystemUser\Business\SecuritySystemUserFacadeInterface getFacade()
 */
class SecuritySystemUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SecuritySystemUser\Business\Reader\SecuritySystemUserReaderInterface
     */
    public function createSecuritySystemUserReader(): SecuritySystemUserReaderInterface
    {
        return new SecuritySystemUserReader(
            $this->getUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SecuritySystemUser\Dependency\Facade\SecuritySystemUserToUserFacadeInterface
     */
    public function getUserFacade(): SecuritySystemUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(SecuritySystemUserDependencyProvider::FACADE_USER);
    }
}
