<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile\Plugin\SessionCustomerValidation;

use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface;

/**
 * @method \Spryker\Client\SessionFile\SessionFileClient getClient()
 * @method \Spryker\Yves\SessionFile\SessionFileFactory getFactory()
 * @method \Spryker\Yves\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileSessionCustomerValidatorPlugin extends AbstractPlugin implements SessionCustomerValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves ID session by `idUser` from File and compare with current.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return bool
     */
    public function isSessionCustomerValid(SessionCustomerTransfer $sessionCustomerTransfer): bool
    {
        return $this->getClient()->isSessionCustomerValid(
            $sessionCustomerTransfer->getIdCustomerOrFail(),
            $sessionCustomerTransfer->getIdSessionOrFail(),
        );
    }
}
