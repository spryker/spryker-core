<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */


namespace SprykerEngine\Shared\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Application;

interface AfterBootExtensionInterface
{

    /**
     * @param Application $app
     */
    public function afterBoot(Application $app);

}
