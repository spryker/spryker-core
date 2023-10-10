<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router\RouterResource;

use SplFileInfo;
use Symfony\Component\Finder\Finder;

class BackofficeRouterResource extends AbstractRouterResource
{
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
            ->filter(function (SplFileInfo $splFileInfo) {
                $pathToController = $splFileInfo->getPathname();
                foreach ($this->config->getNotAllowedBackofficeControllerDirectories() as $notAllowedBackofficeControllerDirectory) {
                    $notAllowedBackofficeControllerDirectoryPath = sprintf('%s%s%s', DIRECTORY_SEPARATOR, $notAllowedBackofficeControllerDirectory, DIRECTORY_SEPARATOR);
                    if (strpos($pathToController, $notAllowedBackofficeControllerDirectoryPath) !== false) {
                        return false;
                    }
                }

                return true;
            });

        return $finder;
    }
}
