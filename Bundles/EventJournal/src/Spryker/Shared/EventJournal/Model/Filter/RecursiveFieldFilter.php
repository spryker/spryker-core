<?php

/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Shared\EventJournal\Model\Filter;

use Spryker\Shared\EventJournal\Model\EventInterface;

class RecursiveFieldFilter extends AbstractFilter
{

    const OPTION_FILTER_PATTERN = 'filter_pattern';

    const OPTION_FILTERED_STR = 'filtered_string';

    const TYPE = 'recursive';

    /**
     * @param EventInterface $event
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
            $this->applyPattern($fields, $pattern);
        }
        return $fields;
    }

    /**
     * @param array $fields
     * @param array $pattern
     */
    protected function applyPattern(array &$fields, array $pattern)
    {
        if (isset($fields[$pattern[0]]) // current pattern part matches
            && is_array($fields[$pattern[0]]) // the value is an array and should be recursively processed
            && isset($pattern[1]) // And there is a next pattern part
        ) {
            $this->applyPattern($fields[$pattern[0]], array_slice($pattern, 1));
        } else if (isset($fields[$pattern[0]])) {
            $fields[$pattern[0]] = $this->options[static::OPTION_FILTERED_STR];
        }
    }
}
