<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Bootstrap\Extension;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Application\Communication\Application;
use Spryker\Shared\Application\Communication\Bootstrap\Extension\BeforeBootExtensionInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Library\DataDirectory;

class BeforeBootExtension implements BeforeBootExtensionInterface
{

    /**
     * @param \Spryker\Shared\Application\Communication\Application $app
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
