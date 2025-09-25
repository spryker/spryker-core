<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Service\UtilDateTime\Model;

use Spryker\Service\UtilDateTime\UtilDateTimeConfig;

class TimezoneReader implements TimezoneReaderInterface
{
    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeConfig
     */
    protected UtilDateTimeConfig $config;

    /**
     * @var string|null
     */
    protected ?string $storeTimezone;

    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeConfig $config
     * @param string|null $storeTimezone
     */
    public function __construct(UtilDateTimeConfig $config, ?string $storeTimezone)
    {
        $this->config = $config;
        $this->storeTimezone = $storeTimezone;
    }

    /**
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->storeTimezone
            ?? $this->config->getDateTimeZone();
    }
}
