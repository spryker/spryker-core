<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate;

use Egulias\EmailValidator\EmailValidator as EguliasEmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation as EguliasRFCValidation;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilValidate\Model\Email\EmailValidator;

class UtilValidateServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilValidate\Model\Email\EmailValidatorInterface
     */
    public function createEmailRfcValidator()
    {
        return new EmailValidator(
            $this->createEguliasEmailValidator(),
            $this->createEguliasRfcValidation()
        );
    }

    /**
     * @return \Egulias\EmailValidator\EmailValidator
     */
    protected function createEguliasEmailValidator()
    {
        return new EguliasEmailValidator();
    }

    /**
     * @return \Egulias\EmailValidator\Validation\RFCValidation
     */
    public function createEguliasRfcValidation()
    {
        return new EguliasRFCValidation();
    }
}
