<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile\Plugin\SessionCustomerValidation;

use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Yves\SessionFile\Plugin\SessionCustomerValidationPage\FileCustomerSessionSaverPlugin} instead.
 *
 * @method \Spryker\Client\SessionFile\SessionFileClient getClient()
 * @method \Spryker\Yves\SessionFile\SessionFileFactory getFactory()
 * @method \Spryker\Yves\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileSessionCustomerSaverPlugin extends AbstractPlugin implements SessionCustomerSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves `SessionCustomer` to file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return void
     */
    public function saveSessionCustomer(SessionCustomerTransfer $sessionCustomerTransfer): void
    {
        $this->getClient()->saveSessionCustomer(
            $sessionCustomerTransfer->getIdCustomerOrFail(),
            $sessionCustomerTransfer->getIdSessionOrFail(),
        );
    }
}
