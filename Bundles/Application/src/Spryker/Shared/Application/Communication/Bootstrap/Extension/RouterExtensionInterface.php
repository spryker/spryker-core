<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;

interface RouterExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     *
     * @return \Symfony\Component\Routing\RouterInterface[]
     */
    public function getRouter(Application $application);

}
