<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router\RouterResource;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

class MerchantPortalRouterResource extends AbstractRouterResource
{
    /**
     * @var string
     */
    protected const MERCHANT_PORTAL_FOLDER_FILTER = 'MerchantPortal';

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()
            ->in($this->config->getControllerDirectories())
            ->name('*Controller.php')
            ->notName('GatewayController.php')
            ->filter(function (SplFileInfo $item) {
                return strpos($item->getPathname(), static::MERCHANT_PORTAL_FOLDER_FILTER) !== false;
            });

        return $finder;
    }
}
