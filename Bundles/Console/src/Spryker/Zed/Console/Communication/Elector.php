<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication;

use Spryker\Zed\Console\Business\Model\Environment;
use Spryker\Zed\Kernel\Communication\FacadeResolverAwareTrait;

/**
 * @deprecated This only exists for backwards compatibility and will be removed with the next major.
 *
 * @method \Spryker\Zed\Console\Business\ConsoleFacade getFacade()
 */
class Elector
{
    use FacadeResolverAwareTrait;

    public function __construct()
    {
        Environment::initialize();
    }

    /**
     * @return bool
     */
    public function isProjectMigratedToApplicationPlugins(): bool
    {
        $serviceProviders = $this->getFacade()->getServiceProviders();

        return (count($serviceProviders) === 0);
    }
}
