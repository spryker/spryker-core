<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatter;

class UtilDateTimeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilDateTime\Model\DateTimeFormatterInterface
     */
    public function createDateFormatter()
    {
        return new DateTimeFormatter(
            $this->getModuleConfig()
        );
    }

    /**
     * @return \Spryker\Shared\Config\Config
     */
    protected function getModuleConfig()
    {
        return $this->getProvidedDependency(UtilDateTimeDependencyProvider::CONFIG);
    }
}
