<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Application;

interface BeforeBootExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $application
     */
    public function beforeBoot(Application $application);

}
