<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Reader\User;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;

interface UserMultiFactorAuthReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $userMultiFactorAuthPlugins
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer
     */
    public function getAvailableUserMultiFactorAuthTypes(
        MultiFactorAuthCriteriaTransfer $multiFactorAuthCriteriaTransfer,
        array $userMultiFactorAuthPlugins
    ): MultiFactorAuthTypesCollectionTransfer;
}
