<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn;

use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeFormat\TimeRangeFormatAbstract;
use SprykerFeature\Zed\Ui\Business\Grid\DateTimeColumn\TimeRangeGenerator\TimeRangeGeneratorInterface;

abstract class FilterFormatAbstract implements FilterFormatInterface
{

    /**
     * @var string
     */
    protected $filterValue;

    /**
     * @param $filterValue
     */
    public function __construct($filterValue)
    {
        $this->filterValue = $filterValue;
    }

    /**
     * @return TimeRangeGeneratorInterface
     */
    abstract public function getTimeRangeGenerator();

    /**
     * @return TimeRangeFormatAbstract[]
     */
    protected function getFormats()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getSuggestions()
    {
        if ($this->getFormatEqualingFilterValue()) {
            $suggestions = $this->getSuggestionEqualingFilterValue();
        } else {
            $suggestions = $this->getSuggestionsStartingWithFilterValue();
        }

        return $suggestions;
    }

    /**
     * @return array
     */
    protected function getSuggestionEqualingFilterValue()
    {
        return [
            $this->getFormatEqualingFilterValue()->getTranslation(),
        ];
    }

    /**
     * @return array
     */
    protected function getSuggestionsStartingWithFilterValue()
    {
        $suggestions = [];
        foreach ($this->getFormatsStartingWithFilterValue() as $format) {
            $suggestions[] = $format->getTranslation();
        }

        return $suggestions;
    }

    /**
     * @return null|TimeRangeFormatAbstract
     */
    protected function getUnambiguousFormat()
    {
        $startingWithFormat = $this->getUnambiguousFormatStartingWithFilterValue();
        $equalFormat = $this->getFormatEqualingFilterValue();

        if ($startingWithFormat || $equalFormat) {
            return $startingWithFormat ? $startingWithFormat : $equalFormat;
        }

        return;
    }

    /**
     * @return null|TimeRangeFormatAbstract
     */
    protected function getUnambiguousFormatStartingWithFilterValue()
    {
        $formats = $this->getFormatsStartingWithFilterValue();

        if (count($formats) === 1) {
            return $formats[0];
        }

        return;
    }

    /**
     * @return array|TimeRangeFormatAbstract[]
     */
    protected function getFormatsStartingWithFilterValue()
    {
        $formats = [];
        foreach ($this->getFormats() as $format) {
            if ($format->translationStartsWith($this->filterValue)) {
                $formats[] = $format;
            }
        }

        return $formats;
    }

    /**
     * @return null|TimeRangeFormatAbstract
     */
    protected function getFormatEqualingFilterValue()
    {
        foreach ($this->getFormats() as $format) {
            if ($format->translationEquals($this->filterValue)) {
                return $format;
            }
        }

        return;
    }

}
