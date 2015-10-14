<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;
use Symfony\Component\Routing\RouterInterface;

interface RouterExtensionInterface
{

    /**
     * @param Application $application
     *
     * @return RouterInterface[]
     */
    public function getRouter(Application $application);

}
