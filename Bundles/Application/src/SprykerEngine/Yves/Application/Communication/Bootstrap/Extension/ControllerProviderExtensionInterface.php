<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Yves\Application\Communication\Plugin\ControllerProviderInterface;

interface ControllerProviderExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return ControllerProviderInterface[]
     */
    public function getControllerProvider(Application $app);

}
