<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Plugin\SessionUserValidation;

use Generated\Shared\Transfer\SessionUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface;

/**
 * @method \Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisSessionUserSaverPlugin extends AbstractPlugin implements SessionUserSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves `SessionUser` to Redis.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return void
     */
    public function saveSessionUser(SessionUserTransfer $sessionUserTransfer): void
    {
        $this->getFactory()->createSessionUserRedisHandler()->saveSessionAccount(
            $sessionUserTransfer->getIdUserOrFail(),
            $sessionUserTransfer->getIdSessionOrFail(),
        );
    }
}
