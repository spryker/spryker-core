<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Vault;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface VaultConstants
{
    /**
     * Specification:
     * - Provides encryption key for vault data.
     *
     * @api
     */
    public const ENCRYPTION_KEY = 'VAULT:ENCRYPTION_KEY';
}
