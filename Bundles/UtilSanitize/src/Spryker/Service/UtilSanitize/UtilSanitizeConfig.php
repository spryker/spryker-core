<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilSanitizeConfig extends AbstractBundleConfig
{
    protected const REPLACEMENT_VALUE = '***';

    /**
     * Specification:
     * - Defines the replacement for any findings of the sanitizer.
     *
     * @api
     *
     * @return string
     */
    public function getStringSanitizerReplacement(): string
    {
        return static::REPLACEMENT_VALUE;
    }
}
