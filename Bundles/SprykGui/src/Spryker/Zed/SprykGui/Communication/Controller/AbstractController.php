<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Spryker\Shared\Config\Environment;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AbstractController extends SprykerAbstractController
{
    protected const MESSAGE_SPRYK_ERROR = 'Spryk available only on Development environment.';

    /**
     * @return bool
     */
    protected function isSprykAvailable(): bool
    {
        if (Environment::isNotDevelopment()) {
            return false;
        }

        return true;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getSprykAvailableErrorRedirectResponse(): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_SPRYK_ERROR);

        return $this->redirectResponse('/', 301);
    }
}
