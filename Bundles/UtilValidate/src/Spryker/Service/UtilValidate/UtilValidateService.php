<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilValidate;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilValidate\UtilValidateServiceFactory getFactory()
 */
class UtilValidateService extends AbstractService implements UtilValidateServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return bool
     */
    public function isEmailFormatValid($email)
    {
        return $this->getFactory()
            ->getEmailValidator()
            ->isFormatValid($email);
    }
}
