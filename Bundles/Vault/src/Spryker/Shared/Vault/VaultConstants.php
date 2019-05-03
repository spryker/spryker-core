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
     * - Mapping vault data type to ecryption key.
     *
     * Example:
     * [
     *     "cart-secret" => "k3*kdjaooiencvh",
     *     "product-abstract-secret" => "kr45rdfgdfg445hhsdf",
     * ]
     *
     * @api
     */
    public const ENCRYPTION_KEYS_PER_TYPE = 'ENCRYPTION_KEYS_PER_TYPE';
}
