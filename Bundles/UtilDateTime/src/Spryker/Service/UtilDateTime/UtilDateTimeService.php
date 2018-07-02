<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilDateTime\UtilDateTimeServiceFactory getFactory()
 */
class UtilDateTimeService extends AbstractService implements UtilDateTimeServiceInterface
{
    /**
     * Specification:
     * - Formats a given datetime string into a configured date
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date)
    {
        return $this->getFactory()->createDateFormatter()->formatDate($date);
    }

    /**
     * Specification:
     * - Formats a given datetime string into a configured datetime
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date)
    {
        return $this->getFactory()->createDateFormatter()->formatDateTime($date);
    }

    /**
     * Specification:
     * - Formats a given datetime string into a configured time
     *
     * @api
     *
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatTime($date)
    {
        return $this->getFactory()->createDateFormatter()->formatTime($date);
    }
}
