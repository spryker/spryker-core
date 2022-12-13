<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidation;

use Generated\Shared\Transfer\SessionCustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidationPage\RedisCustomerSessionSaverPlugin} instead.
 *
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 * @method \Spryker\Yves\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisSessionCustomerSaverPlugin extends AbstractPlugin implements SessionCustomerSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves `SessionCustomer` to Redis.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionCustomerTransfer $sessionCustomerTransfer
     *
     * @return void
     */
    public function saveSessionCustomer(SessionCustomerTransfer $sessionCustomerTransfer): void
    {
        $this->getFactory()->createSessionCustomerRedisHandler()->saveSessionAccount(
            $sessionCustomerTransfer->getIdCustomerOrFail(),
            $sessionCustomerTransfer->getIdSessionOrFail(),
        );
    }
}
