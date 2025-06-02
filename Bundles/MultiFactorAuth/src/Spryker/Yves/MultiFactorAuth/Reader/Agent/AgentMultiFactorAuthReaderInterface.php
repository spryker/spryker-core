<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Reader\Agent;

use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AgentMultiFactorAuthReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAvailableAgentMultiFactorAuthTypes(UserTransfer $userTransfer): MultiFactorAuthTypesCollectionTransfer;

    /**
     * @return bool
     */
    public function isAgentMultiFactorAuthPluginsAvailable(): bool;
}
