<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate\Model\Email;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class RfcValidator implements RfcValidatorInterface
{
    /**
     * @param string $email
     *
     * @return bool
     */
    public function isFormatValid($email)
    {
        $validator = new EmailValidator();
        $validation = new RFCValidation();

        return $validator->isValid($email, $validation);
    }
}
