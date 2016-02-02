<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;
use Symfony\Component\Routing\RouterInterface;

interface RouterExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return RouterInterface[]
     */
    public function getRouter(Application $application);

}
