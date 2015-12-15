<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;

interface TwigExtensionInterface
{

    /**
     * @param Application $application
     */
    public function getTwigExtensions(Application $application);

}
