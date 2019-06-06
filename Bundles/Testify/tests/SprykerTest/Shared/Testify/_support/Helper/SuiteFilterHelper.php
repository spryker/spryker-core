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
    protected const KEY_CONFIG_INCLUDE_GROUPS = 'groups';
    protected const KEY_CONFIG_EXCLUDE_GROUPS = 'skip-group';

    protected const KEY_CONFIG_INCLUSIVE = 'inclusive';
    protected const KEY_CONFIG_EXCLUSIVE = 'exclusive';

    /**
     * @var array
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
     * @return array
     */
    protected function getIncludeGroups(): array
    {
        return $this->options[static::KEY_CONFIG_INCLUDE_GROUPS] ?? [];
    }

    /**
     * @return array
     */
    protected function getExcludeGroups(): array
    {
        return $this->options[static::KEY_CONFIG_EXCLUDE_GROUPS] ?? [];
    }

    /**
     * @return array
     */
    protected function getInclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::KEY_CONFIG_INCLUSIVE);
    }

    /**
     * @return array
     */
    protected function getExclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::KEY_CONFIG_EXCLUSIVE);
    }

    /**
     * @param string $configKey
     *
     * @return array
     */
    protected function getUniqueConfigByKey(string $configKey): array
    {
        if (array_key_exists($configKey, $this->config)) {
            return array_unique($this->config[$configKey]);
        }

        return [];
    }

    /**
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
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addIncludeFilter(Factory $filterFactory): Factory
    {
        $groupsFormConfig = $this->getIncludeGroups();

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
     * @param \PHPUnit\Runner\Filter\Factory $filterFactory
     *
     * @return \PHPUnit\Runner\Filter\Factory
     */
    protected function addExcludeFilter(Factory $filterFactory): Factory
    {
        $groupsFormConfig = $this->getExcludeGroups();

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
