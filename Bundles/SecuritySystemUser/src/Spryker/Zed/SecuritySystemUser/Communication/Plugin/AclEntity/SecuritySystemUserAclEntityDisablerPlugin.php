<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecuritySystemUser\Communication\Plugin\AclEntity;

use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SecuritySystemUser\Communication\SecuritySystemUserCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecuritySystemUser\SecuritySystemUserConfig getConfig()
 * @method \Spryker\Zed\SecuritySystemUser\Business\SecuritySystemUserFacadeInterface getFacade()
 */
class SecuritySystemUserAclEntityDisablerPlugin extends AbstractPlugin implements AclEntityDisablerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Disables AclEntity for system user.
     *
     * @api
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->getFacade()->isAclEntityDisabled();
    }
}
