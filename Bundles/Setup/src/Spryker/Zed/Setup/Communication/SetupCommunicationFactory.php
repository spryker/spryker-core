<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Setup\SetupConfig getConfig()
 * @method \Spryker\Zed\Setup\Business\SetupFacadeInterface getFacade()
 */
class SetupCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @deprecated Will be removed without replacement. Use `vendor/bin/install` instead.
     *
     * @return array
     */
    public function getSetupInstallCommandNames()
    {
        return $this->getConfig()->getSetupInstallCommandNames();
    }
}
