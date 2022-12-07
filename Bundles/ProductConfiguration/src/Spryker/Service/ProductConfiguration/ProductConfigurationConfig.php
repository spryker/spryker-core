<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Spryker\Service\Kernel\AbstractBundleConfig;

class ProductConfigurationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns a list of field names that are not allowed to be used for encoding when generating product configuration instance hash.
     *
     * @api
     *
     * @return list<string>
     */
    public function getConfigurationFieldsNotAllowedForEncoding(): array
    {
        return [];
    }
}
