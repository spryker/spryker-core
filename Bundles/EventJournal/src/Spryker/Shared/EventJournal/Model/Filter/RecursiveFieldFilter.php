<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Shared\EventJournal\Model\Filter;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Shared\EventJournal\Model\EventInterface;

class RecursiveFieldFilter extends AbstractFilter
{

    const OPTION_FILTER_PATTERN = 'filter_pattern';
    const OPTION_FILTERED_STR = 'filtered_string';

    const TYPE = 'recursive';

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function filter(EventInterface $event)
    {
        $event->setFields($this->getFilteredFields($event->getFields()));
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    protected function getFilteredFields(array $fields)
    {
        foreach ($this->options[static::OPTION_FILTER_PATTERN] as $pattern) {
            $fieldKey = array_shift($pattern);
            if (isset($fields[$fieldKey])) {
                $fields[$fieldKey] = $this->applyPattern($fieldKey, $fields[$fieldKey], $pattern);
            }
        }

        return $fields;
    }

    /**
     * @param string|int $key
     * @param mixed $value
     * @param array $pattern
     *
     * @return array|string
     */
    protected function applyPattern($key, $value, array $pattern)
    {
        if (is_array($value)) {
            foreach ($value as $valueKey => $valueValue) {
                $value[$valueKey] = $this->applyPattern($valueKey, $valueValue, $pattern);
            }

            return $value;
        }

        return $this->filterValue($key, $value, $pattern);
    }

    /**
     * @param string|int $key
     * @param mixed $value
     * @param array $pattern
     *
     * @return string
     */
    private function filterValue($key, $value, array $pattern)
    {
        if (in_array($key, $pattern)) {
            return $this->options[static::OPTION_FILTERED_STR];
        }

        return $value;
    }

}
