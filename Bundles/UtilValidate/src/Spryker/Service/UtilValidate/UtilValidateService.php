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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $email
     *
     * @return string
     */
    public function isEmailFormatValid($email)
    {
        return $this->getFactory()
            ->createEmailRfcValidator()
            ->isFormatValid($email);
    }
}
