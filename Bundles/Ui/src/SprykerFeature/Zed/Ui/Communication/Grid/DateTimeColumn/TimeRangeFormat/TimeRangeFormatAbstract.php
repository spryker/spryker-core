<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeFormat;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeGenerator\TimeRangeGeneratorInterface;

abstract class TimeRangeFormatAbstract
{

    /**
     * @var string
     */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * @param array $formats
     *
     * @return array|TimeRangeFormatAbstract[]
     */
    public static function getInstancesFromArray(array $formats)
    {
        $formatHandler = [];
        foreach ($formats as $format) {
            $formatHandler[] = new static($format);
        }

        return $formatHandler;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @throws \ErrorException
     *
     * @return string
     */
    public function getTranslation()
    {
        return __($this->format);
    }

    /**
     * @param $string
     *
     * @return bool
     */
    public function translationStartsWith($string)
    {
        return strpos(strtolower($this->getTranslation()), strtolower($string)) === 0;
    }

    /**
     * @param $string
     *
     * @return bool
     */
    public function translationEquals($string)
    {
        return strtolower($this->getTranslation()) === strtolower($string);
    }

    /**
     * @param Carbon $carbonData
     *
     * @return TimeRangeGeneratorInterface
     */
    abstract public function getTimeRangeGenerator(Carbon $carbonData);

}
