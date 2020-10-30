<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductConfiguration;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductConfigurationConstants
{
    /**
     * Specification:
     * - Provides encryption key for checksum validation.
     * - Used for the product configurator data checksum generation based on the given key.
     *
     * @api
     */
    public const SPRYKER_PRODUCT_CONFIGURATOR_ENCRYPTION_KEY = 'SPRYKER_PRODUCT_CONFIGURATOR_ENCRYPTION_KEY';

    /**
     * Specification:
     * - Provides hex initialization vector for checksum validation.
     * - Used for the product configurator data checksum generation as hex initialization vector.
     *
     * @api
     */
    public const SPRYKER_PRODUCT_CONFIGURATOR_HEX_INITIALIZATION_VECTOR = 'SPRYKER_PRODUCT_CONFIGURATOR_HEX_INITIALIZATION_VECTOR';
}
