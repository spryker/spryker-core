<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDateTime\Model;

use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Shared\Twig\TwigFilter;

class DateTimeFormatterTwigExtension extends TwigExtension
{
    const EXTENSION_NAME = 'DateTimeFormatterTwigExtension';

    /**
     * @var array
     */
    protected $filterFunctions = [
        'formatDate',
        'formatDateTime',
        'formatTime',
    ];

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     */
    public function __construct(UtilDateTimeServiceInterface $utilDateTimeService)
    {
        $this->utilDateTimeService = $utilDateTimeService;
    }

    /**
     * @return array
     */
    public function getFilters()
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
     * @return array
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::EXTENSION_NAME;
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date)
    {
        return $this->utilDateTimeService->formatDate($date);
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDateTime($date)
    {
        return $this->utilDateTimeService->formatDateTime($date);
    }

    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatTime($date)
    {
        return $this->utilDateTimeService->formatTime($date);
    }
}
