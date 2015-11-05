<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Twig;

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
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateShort($date, $timezone = null)
    {
        return $this->dateFormatter->dateShort($date, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateMedium($date, $timezone = null)
    {
        return $this->dateFormatter->dateMedium($date, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateRFC($date, $timezone = null)
    {
        return $this->dateFormatter->dateRFC($date, $this->convertDateTimeZone($timezone));
    }

    /**
     * @param string $date
     * @param string|null $timezone
     *
     * @return string
     */
    public function formatDateTime($date, $timezone = null)
    {
        return $this->dateFormatter->dateTime($date, $this->convertDateTimeZone($timezone));
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
