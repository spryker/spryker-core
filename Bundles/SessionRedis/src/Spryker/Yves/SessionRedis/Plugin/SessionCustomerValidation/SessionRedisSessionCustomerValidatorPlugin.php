<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidation;

use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidationPage\RedisCustomerSessionValidatorPlugin} instead.
 *
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisSessionCustomerValidatorPlugin extends AbstractPlugin implements SessionCustomerValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves ID session by `idUser` from redis and compare with current.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return bool
     */
    public function isSessionCustomerValid(SessionCustomerTransfer $sessionCustomerTransfer): bool
    {
        return $this->getFactory()->createSessionCustomerRedisHandler()->isSessionAccountValid(
            $sessionCustomerTransfer->getIdCustomerOrFail(),
            $sessionCustomerTransfer->getIdSessionOrFail(),
        );
    }
}
