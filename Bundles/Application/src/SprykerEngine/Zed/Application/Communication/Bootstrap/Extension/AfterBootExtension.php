<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\AfterBootExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Application\ApplicationConstants;

class AfterBootExtension implements AfterBootExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function afterBoot(Application $app)
    {
        $app['monolog.level'] = Config::get(ApplicationConstants::LOG_LEVEL);
    }

}
