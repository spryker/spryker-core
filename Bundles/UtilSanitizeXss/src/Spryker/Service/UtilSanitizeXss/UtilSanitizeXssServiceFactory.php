<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitizeXss;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface;

class UtilSanitizeXssServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilSanitizeXss\Dependency\External\UtilSanitizeToXssSanitizeInterface
     */
    public function getXssSanitizer(): UtilSanitizeToXssSanitizeInterface
    {
        return $this->getProvidedDependency(UtilSanitizeXssDependencyProvider::XSS_SANITIZER);
    }
}
