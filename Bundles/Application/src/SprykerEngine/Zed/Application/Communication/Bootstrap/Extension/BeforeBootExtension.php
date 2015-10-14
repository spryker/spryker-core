<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Application\Communication\Bootstrap\Extension;

use SprykerEngine\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface;
use SprykerEngine\Shared\Application\Communication\Application;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Library\DataDirectory;
use SprykerFeature\Shared\Library\Environment;

class BeforeBootExtension implements BeforeBootExtensionInterface
{

    /**
     * @param Application $app
     */
    public function beforeBoot(Application $app)
    {
        $app['locale'] = Store::getInstance()->getCurrentLocale();
        if (Environment::isDevelopment()) {
            $app['profiler.cache_dir'] = DataDirectory::getLocalStoreSpecificPath('cache/profiler');
        }
    }

}
