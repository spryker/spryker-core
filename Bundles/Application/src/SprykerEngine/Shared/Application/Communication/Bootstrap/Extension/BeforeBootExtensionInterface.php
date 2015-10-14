<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerEngine\Shared\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;

interface BeforeBootExtensionInterface
{

    /**
     * @param Application $app
     */
    public function beforeBoot(Application $app);

}
