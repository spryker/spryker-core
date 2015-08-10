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
     * @param string $date
     * @param string|null $context
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateShort($date, $context = null, $timezone = null)
    {
        return $this->dateFormatter->dateShort($date, $context, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $context
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateMedium($date, $context = null, $timezone = null)
    {
        return $this->dateFormatter->dateMedium($date, $context, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $context
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateRFC($date, $context = null, $timezone = null)
    {
        return $this->dateFormatter->dateRFC($date, $context, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $context
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTime($date, $context = null, $timezone = null)
    {
        return $this->dateFormatter->dateTime($date, $context, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string|null $timezone
     *
     * @return \DateTimeZone|null
     */
    private function convertDateTimeZone($timezone = null)
    {
        if ($timezone !== null) {
            return new \DateTimeZone($timezone);
        }

        return null;
    }
}
