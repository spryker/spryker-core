<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedis\Communication\Plugin\SessionUserValidation;

use Generated\Shared\Transfer\SessionUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;

/**
 * @method \Spryker\Zed\SessionRedis\Communication\SessionRedisCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionRedis\SessionRedisConfig getConfig()
 */
class SessionRedisSessionUserValidatorPlugin extends AbstractPlugin implements SessionUserValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves ID session by `idUser` from redis and compare with current.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return bool
     */
    public function isSessionUserValid(SessionUserTransfer $sessionUserTransfer): bool
    {
        return $this->getFactory()->createSessionUserRedisHandler()->isSessionAccountValid(
            $sessionUserTransfer->getIdUserOrFail(),
            $sessionUserTransfer->getIdSessionOrFail(),
        );
    }
}
