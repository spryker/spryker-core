<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidationPage;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionSaverPluginInterface;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 */
class RedisCustomerSessionSaverPlugin extends AbstractPlugin implements CustomerSessionSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SessionEntityRequestTransfer.idEntity` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.idSession` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.entityType` transfer property to be set.
     * - Saves customer's session data to Redis storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function saveSession(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        return $this->getFactory()
            ->createSessionEntitySaver()
            ->save($sessionEntityRequestTransfer);
    }
}
