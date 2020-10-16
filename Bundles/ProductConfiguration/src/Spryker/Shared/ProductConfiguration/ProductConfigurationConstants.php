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
     *
     * @api
     */
    public const SPRYKER_CONFIGURATOR_ENCRYPTION_KEY = 'SPRYKER_CONFIGURATOR_ENCRYPTION_KEY';
}
