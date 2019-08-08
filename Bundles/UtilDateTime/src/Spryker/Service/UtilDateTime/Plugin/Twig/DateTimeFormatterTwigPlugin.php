<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime\Plugin\Twig;

use Spryker\Service\Twig\Plugin\AbstractTwigExtensionPlugin;
use Spryker\Shared\Twig\TwigFilter;

/**
 * @method \Spryker\Service\UtilDateTime\UtilDateTimeService getService()
 */
class DateTimeFormatterTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @var array
     */
    protected $filterFunctions = [
        'formatDate',
        'formatDateTime',
        'formatTime',
    ];

    /**
     * @return \Spryker\Shared\Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        $filters = [];

        foreach ($this->filterFunctions as $dateFormatterFunction) {
            $filters[] = new TwigFilter(
                $dateFormatterFunction,
                [$this, $dateFormatterFunction],
                ['is_safe' => ['html']]
            );
        }

        return $filters;
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date): string
    {
        return $this->getService()->formatDate($date);
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date): string
    {
        return $this->getService()->formatDateTime($date);
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatTime($date): string
    {
        return $this->getService()->formatTime($date);
    }
}
