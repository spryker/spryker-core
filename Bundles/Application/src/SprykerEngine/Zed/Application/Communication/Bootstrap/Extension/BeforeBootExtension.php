<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Library\DataDirectory;

class BeforeBootExtension implements BeforeBootExtensionInterface
{

    /**
     * @param Application $app
     *
     * @return void
     */
    public function beforeBoot(Application $app)
    {
        $app['locale'] = Store::getInstance()->getCurrentLocale();

        if (Config::get(ApplicationConstants::ENABLE_WEB_PROFILER, false)) {
            $app['profiler.cache_dir'] = DataDirectory::getLocalStoreSpecificPath('cache/profiler');
        }
    }

}
