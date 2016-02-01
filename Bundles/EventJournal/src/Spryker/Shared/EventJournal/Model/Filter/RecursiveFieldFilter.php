<?php

/**
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
            $this->applyPattern($fields, $pattern);
        }

        return $fields;
    }

    /**
     * @param array $fields
     * @param array $pattern
     *
     * @return void
     */
    protected function applyPattern(array &$fields, array $pattern)
    {
        if ($this->currentPatternPartMatches($fields, $pattern)
            && $this->valueIsArray($fields, $pattern)
            && $this->nextPatternPartExists($pattern)
        ) {
            $this->applyPattern($fields[$pattern[0]], array_slice($pattern, 1));
        } elseif ($this->currentPatternPartMatches($fields, $pattern)) {
            $fields[$pattern[0]] = $this->options[static::OPTION_FILTERED_STR];
        }
    }

    /**
     * @param array $fields
     * @param array $pattern
     *
     * @return bool
     */
    protected function currentPatternPartMatches(array &$fields, array $pattern)
    {
        return isset($fields[$pattern[0]]);
    }

    /**
     * @param array $fields
     * @param array $pattern
     *
     * @return bool
     */
    protected function valueIsArray(array &$fields, array $pattern)
    {
        return is_array($fields[$pattern[0]]);
    }

    /**
     * @param array $pattern
     *
     * @return bool
     */
    protected function nextPatternPartExists(array $pattern)
    {
        return isset($pattern[1]);
    }

}
