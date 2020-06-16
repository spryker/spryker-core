<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Validator;

use Symfony\Component\HttpFoundation\Response;

interface RedirectUrlValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return void
     */
    public function validateRedirectUrl(Response $response): void;
}
