<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Bootstrap\Extension\AfterBootExtensionInterface;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Config;
use Spryker\Shared\Application\ApplicationConstants;

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
