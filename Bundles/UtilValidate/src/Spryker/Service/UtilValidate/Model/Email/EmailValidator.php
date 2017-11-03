<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate\Model\Email;

use Egulias\EmailValidator\EmailValidator as EguliasEmailValidator;
use Egulias\EmailValidator\Validation\EmailValidation as EguliasEmailValidation;

class EmailValidator implements EmailValidatorInterface
{
    /**
     * @var \Egulias\EmailValidator\EmailValidator
     */
    protected $emailValidator;

    /**
     * @var \Egulias\EmailValidator\Validation\EmailValidation
     */
    protected $emailValidation;

    /**
     * @param \Egulias\EmailValidator\EmailValidator $emailValidator
     * @param \Egulias\EmailValidator\Validation\EmailValidation $emailValidation
     */
    public function __construct(EguliasEmailValidator $emailValidator, EguliasEmailValidation $emailValidation)
    {
        $this->emailValidator = $emailValidator;
        $this->emailValidation = $emailValidation;
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
