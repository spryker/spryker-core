<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use Carbon\Carbon;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\DayFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\MonthFormat;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\YearFormat;

/**
 * @TODO the whole getSuggestions() logic of this time filter sucks in combination with the gridview.js
 */
class DateTimeString extends FilterFormatAbstract
{

    /**
     * @var array
     */
    protected $formatLetters = [
        'Y' => [
            'min' => 2000,
            'max' => 2100,
            'min_length' => 4,
            'max_length' => 4,
        ],
        'n' => [
            'min' => 1,
            'max' => 12,
            'min_length' => 1,
            'max_length' => 2,
        ],
        'm' => [
            'min' => 1,
            'max' => 12,
            'min_length' => 2,
            'max_length' => 2,
            'zero_prefix' => true,
        ],
        'j' => [
            'min' => 1,
            'max' => 31,
            'min_length' => 1,
            'max_length' => 2,
        ],
        'd' => [
            'min' => 1,
            'max' => 31,
            'min_length' => 2,
            'max_length' => 2,
            'zero_prefix' => true,
        ],
    ];

    /**
     */
    public function getTimeRangeGenerator()
    {
        $format = $this->getUnambiguousFormat();

        if (!$format) {
            return;
        }

        $carbonDate = Carbon::createFromFormat($format->getFormat(), $this->filterValue);

        return $format->getTimeRangeGenerator($carbonDate);
    }

    /**
     * @return array
     */
    public function getSuggestions()
    {
        $suggestedValues = [];
        $suggestions = [];

        $format = $this->getUnambiguousFormat();
        if ($format && strlen($this->filterValue) === 4) {
            return [$this->filterValue];
        }

        foreach ($this->formatLetters as $letter => $properties) {
            $suggestedValue = $this->suggestValue($this->filterValue, $properties);
            if ($suggestedValue) {
                $suggestedValues[$letter] = $suggestedValue;
            }
        }

        foreach ($suggestedValues as $suggestionKey => $value) {
            foreach ($this->getFormats() as $format) {
                if ($format->getFormat()[strlen($format->getFormat()) - 1] === '/') {
                    continue;
                }

                if ($suggestionKey === $this->getFormatLetter($format->getFormat(), 0)) {
                    $c = 1;
                    $suggestion = $value;
                    while ($this->getFormatLetter($format->getFormat(), $c)) {
                        $suggestion = $suggestion . '/' . $this->generateFormatValue($this->formatLetters[$this->getFormatLetter($format->getFormat(), $c)]);
                        $c++;
                    }
                    $suggestions[] = $suggestion;
                }
            }
        }

        return $suggestions;
    }

    /**
     * @return array|TimeRangeFormat\TimeRangeFormatAbstract[]
     */
    protected function getFormats()
    {
        $yearFormats = YearFormat::getInstancesFromArray([
            'Y',
            'Y/',
        ]);

        $monthFormats = MonthFormat::getInstancesFromArray([
            'Y/n',
            'Y/m',
            'Y/n/',
            'Y/m/',
        ]);

        $dayFormats = DayFormat::getInstancesFromArray([
            'n/j',
            'm/d',
            'Y/n/j',
            'Y/m/d',
        ]);

        return array_merge($yearFormats, $monthFormats, $dayFormats);
    }

    /**
     * @return null|TimeRangeFormat\TimeRangeFormatAbstract
     */
    protected function getUnambiguousFormat()
    {
        foreach ($this->getFormats() as $format) {
            $filterValueMatchesFormat = self::isStringMatchingDateFormat($this->filterValue, $format->getFormat());
            if ($filterValueMatchesFormat) {
                return $format;
            }
        }

        return;
    }

    /**
     * @param $string
     * @param $format
     *
     * @return bool
     */
    public static function isStringMatchingDateFormat($string, $format)
    {
        $dateTime = \DateTime::createFromFormat($format, $string);

        return $dateTime && $dateTime->format($format) === $string;
    }

    /**
     * @param array $formatLetter
     *
     * @return int|string
     */
    protected function generateFormatValue(array $formatLetter)
    {
        $rand = rand(1, 9);
        if (isset($formatLetter['zero_prefix']) && $formatLetter['zero_prefix']) {
            $rand = '0' . $rand;
        }

        return $rand;
    }

    /**
     * @param $value
     * @param array $formatLetter
     *
     * @return null|string
     */
    protected function suggestValue($value, array $formatLetter)
    {
        if ($value[0] === '0') {
            if (isset($formatLetter['zero_prefix']) && $formatLetter['zero_prefix']) {
                if (strlen($value) === 2) {
                    return $value;
                } elseif (strlen($value) === 1) {
                    return 0 . rand(1, 9);
                }
            } else {
                return;
            }
        }

        if (strlen($value) < $formatLetter['min_length']) {
            for ($i = strlen($value); $i < $formatLetter['min_length']; $i++) {
                $value = $value . '0';
            }
        } elseif (strlen($value) > $formatLetter['max_length']) {
            return;
        }

        if ($value > $formatLetter['max'] || $value < $formatLetter['min']) {
            return;
        }

        return $value;
    }

    /**
     * @param string $format
     * @param int $position
     */
    protected function getFormatLetter($format, $position)
    {
        if (isset($format[0 + $position * 2])) {
            return $format[0 + $position * 2];
        }

        return;
    }

}
