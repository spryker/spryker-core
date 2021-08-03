<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Plugin\AclEntity;

use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Console\ConsoleConfig getConfig()
 * @method \Spryker\Zed\Console\Business\ConsoleFacadeInterface getFacade()
 * @method \Spryker\Zed\Console\Communication\ConsoleCommunicationFactory getFactory()
 */
class ConsoleAclEntityDisablerPlugin extends AbstractPlugin implements AclEntityDisablerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Disables AclEntity for cli.
     *
     * @api
     *
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->getConfig()->isPhpSapiEqualCli();
    }
}
