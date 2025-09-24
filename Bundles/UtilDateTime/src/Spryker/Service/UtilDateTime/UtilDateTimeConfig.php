<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Service\UtilDateTime;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\UtilDateTime\UtilDateTimeConstants;

class UtilDateTimeConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const DEFAULT_TIME_ZONE = 'Europe/Berlin';

    /**
     * Specification:
     * - Returns time zone from application config, falls back to default if not provided.
     *
     * @api
     *
     * @return string
     */
    public function getDateTimeZone(): string
    {
        return $this->get(UtilDateTimeConstants::DATE_TIME_ZONE, static::DEFAULT_TIME_ZONE);
    }
}
