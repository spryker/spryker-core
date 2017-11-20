<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Business;

use Spryker\Shared\Log\Sanitizer\Sanitizer;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class LogBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Shared\Log\Sanitizer\SanitizerInterface
     */
    public function createSanitizer()
    {
        return new Sanitizer(
            $this->getConfig()->getSanitizerFieldNames(),
            $this->getConfig()->getSanitizedFieldValue()
        );
    }
}
