<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Dependency\Facade;

interface MerchantRelationRequestToPermissionFacadeInterface
{
    /**
     * @param string $permissionKey
     * @param string|int $identifier
     * @param array<mixed>|string|int|null $context
     *
     * @return bool
     */
    public function can(string $permissionKey, string|int $identifier, $context = null): bool;
}
