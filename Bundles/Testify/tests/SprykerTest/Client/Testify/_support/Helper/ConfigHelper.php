<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

use SprykerTest\Shared\Testify\Helper\ConfigHelper as HelperConfigHelper;

class ConfigHelper extends HelperConfigHelper
{
    /**
     * @var string
     */
    protected const CONFIG_CLASS_NAME_PATTERN = '\%1$s\Client\%3$s\%3$sConfig';
}
