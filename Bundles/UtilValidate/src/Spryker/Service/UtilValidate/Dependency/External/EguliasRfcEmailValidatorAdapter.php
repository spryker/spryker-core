<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate\Dependency\External;

use Egulias\EmailValidator\EmailValidator as EguliasEmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation as EguliasRfcValidation;

class EguliasRfcEmailValidatorAdapter implements EmailValidatorAdapterInterface
{
    /**
     * @var \Egulias\EmailValidator\EmailValidator
     */
    protected $emailValidator;

    /**
     * @var \Egulias\EmailValidator\Validation\EmailValidation
     */
    protected $emailValidation;

    public function __construct()
    {
        $this->emailValidator = new EguliasEmailValidator();
        $this->emailValidation = new EguliasRfcValidation();
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function isFormatValid($email)
    {
        return $this->emailValidator->isValid($email, $this->emailValidation);
    }
}
