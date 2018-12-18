<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Controller;

use Spryker\Shared\Config\Environment;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController as SprykerAbstractController;

/**
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class AbstractController extends SprykerAbstractController
{
    protected const MESSAGE_SPRYK_ERROR = 'Spryk available only on Development environment.';

    /**
     * @return bool
     */
    public function isSprykAvailable(): bool
    {
        $isDevelopmentEnvironment = Environment::isDevelopment();
        $isCli = PHP_SAPI === 'cli';

        if ($isDevelopmentEnvironment || $isCli) {
            return true;
        }

        return false;
    }
}
