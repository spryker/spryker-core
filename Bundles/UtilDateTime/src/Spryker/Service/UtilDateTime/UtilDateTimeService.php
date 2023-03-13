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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \DateTime|string $dateTime
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTimeToIso8601($dateTime, ?string $timezone = null): string
    {
        return $this->getFactory()->createDateFormatter()->formatDateTimeToIso8601($dateTime, $timezone);
    }

    /**
     * {@inheritDoc}
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
