<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\SessionCustomerValidationPage;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SessionCustomerValidationPageExtension\Dependency\Plugin\CustomerSessionValidatorPluginInterface;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 */
class RedisCustomerSessionValidatorPlugin extends AbstractPlugin implements CustomerSessionValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SessionEntityRequestTransfer.idEntity` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.idSession` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.entityType` transfer property to be set.
     * - Retrieves session ID by entity ID from Redis storage.
     * - Returns `SessionEntityResponseTransfer.isSuccessfull=true` if retrieved session ID equals to the provided one.
     * - Returns `SessionEntityResponseTransfer.isSuccessfull=false`, otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SessionEntityResponseTransfer
     */
    public function validate(SessionEntityRequestTransfer $sessionEntityRequestTransfer): SessionEntityResponseTransfer
    {
        return $this->getFactory()
            ->createSessionEntityValidator()
            ->validate($sessionEntityRequestTransfer);
    }
}
