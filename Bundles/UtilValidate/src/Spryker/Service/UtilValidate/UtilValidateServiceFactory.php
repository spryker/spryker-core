<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilValidate\Model\Email\RfcValidator;

class UtilValidateServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilValidate\Model\Email\RfcValidatorInterface
     */
    public function createEmailRfcValidator()
    {
        return new RfcValidator();
    }
}
