<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\Validator\UrlValidator;
use Spryker\Yves\Kernel\Validator\UrlValidatorInterface;

class KernelFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Kernel\Validator\UrlValidatorInterface
     */
    public function createUrlValidator(): UrlValidatorInterface
    {
        return new UrlValidator();
    }
}
