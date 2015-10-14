<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\RouterExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerFeature\Shared\Application\Business\Routing\SilexRouter;
use SprykerFeature\Zed\Application\Business\Model\Router\MvcRouter;
use Symfony\Component\Routing\RouterInterface;

class RouterExtension implements RouterExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return RouterInterface[]
     */
    public function getRouter(Application $app)
    {
        return [
            new MvcRouter($app),
            new SilexRouter($app),
        ];
    }

}
