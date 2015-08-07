<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Twig;

use SprykerFeature\Shared\Library\DateFormatter;

class DateFormatterTwigExtension extends \Twig_Extension
{
    private static $filterFunctions = [
        'formatDateShort',
        'formatDateMedium',
        'formatDateRFC',
        'formatDateTime',
    ];

    /**
     * @var DateFormatter
     */
    private $dateFormatter;

    /**
     * @param DateFormatter $dateFormatter
     */
    public function __construct(DateFormatter $dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $filters = [];

        foreach (self::$filterFunctions as $dateFormatterFunction) {
            $filters[] = new \Twig_SimpleFilter(
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
        return 'DateFormatterTwigExtension';
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function formatDateShort($date)
    {
        return $this->dateFormatter->dateShort($date);
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function formatDateMedium($date)
    {
        return $this->dateFormatter->dateMedium($date);
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function formatDateRFC($date)
    {
        return $this->dateFormatter->dateRFC($date);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    public function formatDateTime($date)
    {
        return $this->dateFormatter->dateTime($date);
    }
}
