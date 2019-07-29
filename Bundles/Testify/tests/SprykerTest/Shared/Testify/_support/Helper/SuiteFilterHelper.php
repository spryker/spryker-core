<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Platform\Extension;
use PHPUnit\Runner\Filter\ExcludeGroupFilterIterator;
use PHPUnit\Runner\Filter\Factory;
use PHPUnit\Runner\Filter\IncludeGroupFilterIterator;
use ReflectionClass;
use SprykerTest\Shared\Testify\Filter\InclusiveGroupFilterIterator;

class SuiteFilterHelper extends Extension
{
    protected const CONFIG_KEY_INCLUDE = 'groups';
    protected const CONFIG_KEY_EXCLUDE = 'skip-group';

    protected const CONFIG_KEY_INCLUSIVE = 'inclusive';
    protected const CONFIG_KEY_EXCLUSIVE = 'exclusive';

    /**
     * @var string[]
     */
    public static $events = [
        Events::SUITE_BEFORE => 'filterSuiteByGroups',
    ];

    /**
     * @param \Codeception\Event\SuiteEvent $e
     *
     * @return void
     */
    public function filterSuiteByGroups(SuiteEvent $e): void
    {
        $testSuite = $e->getSuite();

        $filterFactory = new Factory();

        $filterFactory = $this->addExclusiveFilter($filterFactory);
        $filterFactory = $this->addInclusiveFilter($filterFactory);

        $filterFactory = $this->addExcludeFilter($filterFactory);
        $filterFactory = $this->addIncludeFilter($filterFactory);

        $testSuite->injectFilter($filterFactory);
    }

    /**
     * @return string[]
     */
    protected function getIncludeParameter(): array
    {
        return $this->options[static::CONFIG_KEY_INCLUDE] ?? [];
    }

    /**
     * @return string[]
     */
    protected function getExcludeParameter(): array
    {
        return $this->options[static::CONFIG_KEY_EXCLUDE] ?? [];
    }

    /**
     * @return string[]
     */
    protected function getInclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::CONFIG_KEY_INCLUSIVE);
    }

    /**
     * @return string[]
     */
    protected function getExclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::CONFIG_KEY_EXCLUSIVE);
    }

    /**
     * @param string $configKey
     *
     * @return string[]
     */
    protected function getUniqueConfigByKey(string $configKey): array
    {
        if (array_key_exists($configKey, $this->config)) {
            return array_unique($this->config[$configKey]);
        }

        return [];
    }

    /**
     * Adds filter to exclude tests with ANY of groups defined in extention configuration.
     *
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addExclusiveFilter(Factory $filterFactory): Factory
    {
        $exclusiveGroups = $this->getExclusiveGroups();

        if (count($exclusiveGroups) === 0) {
            return $filterFactory;
        }

        $filterFactory->addFilter(
            new ReflectionClass(ExcludeGroupFilterIterator::class),
            $exclusiveGroups
        );

        return $filterFactory;
    }

    /**
     * Adds filter to include tests with ALL groups defined in extention configuration.
     *
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addInclusiveFilter(Factory $filterFactory): Factory
    {
        $inclusiveGroups = $this->getInclusiveGroups();

        if (count($inclusiveGroups) === 0) {
            return $filterFactory;
        }

        $filterFactory->addFilter(
            new ReflectionClass(InclusiveGroupFilterIterator::class),
            $inclusiveGroups
        );

        return $filterFactory;
    }

    /**
     * Adds filter to include tests with ANY of groups defined in command arguments.
     *
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addIncludeFilter(Factory $filterFactory): Factory
    {
        $groupsFormConfig = $this->getIncludeParameter();

        if (count($groupsFormConfig) === 0) {
            return $filterFactory;
        }

        $filterFactory->addFilter(
            new ReflectionClass(IncludeGroupFilterIterator::class),
            $groupsFormConfig
        );

        return $filterFactory;
    }

    /**
     * Adds filter to exclude tests with ANY of groups defined in command arguments.
     *
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addExcludeFilter(Factory $filterFactory): Factory
    {
        $groupsFormConfig = $this->getExcludeParameter();

        if (count($groupsFormConfig) === 0) {
            return $filterFactory;
        }

        $filterFactory->addFilter(
            new ReflectionClass(ExcludeGroupFilterIterator::class),
            $groupsFormConfig
        );

        return $filterFactory;
    }
}
