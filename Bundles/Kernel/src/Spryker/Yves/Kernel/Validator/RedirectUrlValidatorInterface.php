<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\Validator;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

interface RedirectUrlValidatorInterface
{
    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     *
     * @throws \Spryker\Yves\Kernel\Exception\ForbiddenExternalRedirectException
     *
     * @return void
     */
    public function validateRedirectUrl(ResponseEvent $event): void;
}
