<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Storage\KeyBuilder;

use Generated\Shared\Transfer\SecurityCheckAuthContextTransfer;

interface SecurityBlockerStorageKeyBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer
     *
     * @return string
     */
    public function getStorageKey(SecurityCheckAuthContextTransfer $securityCheckAuthContextTransfer): string;
}
