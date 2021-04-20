<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilSanitizeConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getStringSanitizerReplacement(): string
    {
        return '***';
    }
}
