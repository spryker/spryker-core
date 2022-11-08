<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionFile\Plugin\SessionAgentValidation;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;
use Generated\Shared\Transfer\SessionEntityResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SessionAgentValidationExtension\Dependency\Plugin\SessionAgentValidatorPluginInterface;

/**
 * @method \Spryker\Client\SessionFile\SessionFileClient getClient()
 * @method \Spryker\Yves\SessionFile\SessionFileFactory getFactory()
 * @method \Spryker\Yves\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileSessionAgentValidatorPlugin extends AbstractPlugin implements SessionAgentValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SessionEntityRequestTransfer.idEntity` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.idSession` transfer property to be set.
     * - Requires `SessionEntityRequestTransfer.entityType` transfer property to be set.
     * - Retrieves session ID by entity ID from a file.
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
