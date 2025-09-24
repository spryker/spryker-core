<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatter;
use Spryker\Service\UtilDateTime\Model\TimezoneReader;
use Spryker\Service\UtilDateTime\Model\TimezoneReaderInterface;

/**
 * @method \Spryker\Service\UtilDateTime\UtilDateTimeConfig getConfig()
 */
class UtilDateTimeServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilDateTime\Model\DateTimeFormatterInterface
     */
    public function createDateFormatter()
    {
        return new DateTimeFormatter(
            $this->getModuleConfig(),
            $this->createTimezoneReader(),
        );
    }

    /**
     * @return \Spryker\Service\UtilDateTime\Model\TimezoneReaderInterface
     */
    public function createTimezoneReader(): TimezoneReaderInterface
    {
        return new TimezoneReader(
            $this->getConfig(),
            $this->getCurrentTimezone(),
        );
    }

    /**
     * @return string|null
     */
    public function getCurrentTimezone(): ?string
    {
        return $this->getProvidedDependency(UtilDateTimeDependencyProvider::SERVICE_TIMEZONE);
    }

    /**
     * @return \Spryker\Shared\Config\Config
     */
    protected function getModuleConfig()
    {
        return $this->getProvidedDependency(UtilDateTimeDependencyProvider::CONFIG);
    }
}
