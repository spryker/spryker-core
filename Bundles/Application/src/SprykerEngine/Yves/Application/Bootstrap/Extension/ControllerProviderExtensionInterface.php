<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Yves\Application\Plugin\ControllerProviderInterface;

interface ControllerProviderExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return ControllerProviderInterface[]
     */
    public function getControllerProvider(Application $app);

}
