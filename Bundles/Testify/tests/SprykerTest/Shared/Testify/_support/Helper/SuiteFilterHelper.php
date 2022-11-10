<?php
// phpcs:ignoreFile
// PHPStan failed on this fail: An error occurred during processing; checking has been
// aborted. The error message was: Undefined property:  PHPStan\PhpDocParser\Ast\PhpDoc\TypelessParamTagValueNode::$type

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\TestInterface;
use PHPUnit\Runner\Filter\ExcludeGroupFilterIterator;
use PHPUnit\Runner\Filter\Factory;
use PHPUnit\Runner\Filter\IncludeGroupFilterIterator;
use ReflectionClass;
use Codeception\Extension;
use SprykerTest\Shared\Testify\Filter\InclusiveGroupFilterIterator;

class SuiteFilterHelper extends Extension
{
    /**
     * @var string
     */
    protected const CONFIG_KEY_INCLUDE = 'groups';

    /**
     * @var string
     */
    protected const CONFIG_KEY_EXCLUDE = 'skip-group';

    /**
     * @var string
     */
    protected const CONFIG_KEY_INCLUSIVE = 'inclusive';

    /**
     * @var string
     */
    protected const CONFIG_KEY_EXCLUSIVE = 'exclusive';

    /**
     * @var array<string>
     */
    public static $events = [
        'suite.start' => 'filterSuiteByGroupsCodeception5',
    ];

    /**
     * @param \Codeception\Event\SuiteEvent $e
     *
     * @return void
     */
    public function filterSuiteByGroupsCodeception5(SuiteEvent $e): void
    {
        $testSuite = $e->getSuite();
        $tests = [];

        foreach ($testSuite->getTests() as $test) {
            if ($test instanceof TestInterface) {
                $groups = $test->getMetadata()->getGroups();

                if ($this->isTestAllowedbyGroups($groups)) {
                    $tests[] = $test;
                }
            }
        }

        $this->setSuteTests($testSuite, $tests);
    }

    /**
     * @return array<string>
     */
    protected function getIncludeParameter(): array
    {
        return $this->options[static::CONFIG_KEY_INCLUDE] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getExcludeParameter(): array
    {
        return $this->options[static::CONFIG_KEY_EXCLUDE] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getInclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::CONFIG_KEY_INCLUSIVE);
    }

    /**
     * @return array<string>
     */
    protected function getExclusiveGroups(): array
    {
        return $this->getUniqueConfigByKey(static::CONFIG_KEY_EXCLUSIVE);
    }

    /**
     * @param string $configKey
     *
     * @return array<string>
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
            $exclusiveGroups,
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
            $inclusiveGroups,
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
            $groupsFormConfig,
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
            $groupsFormConfig,
        );

        return $filterFactory;
    }

    /**
     * @param $testSuite
     * @param array $tests
     * @return void
     * @throws \ReflectionException
     */
    protected function setSuteTests($testSuite, array $tests)
    {
        $refClass = new ReflectionClass($testSuite);
        $reflectionProperty = $refClass->getProperty('tests');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($testSuite, $tests);
    }

    /**
     * @param array $groups
     * @return bool
     */
    public function isTestAllowedbyGroups(array $groups): bool
    {
        $inclusiveGroups = $this->getInclusiveGroups();
        $exclusiveGroups = $this->getExclusiveGroups();

        if ($inclusiveGroups !== [] && count(array_intersect($groups, $inclusiveGroups)) !== count($inclusiveGroups)) {
            return false;
        }

        if ($exclusiveGroups !== [] && count(array_intersect($groups, $exclusiveGroups)) > 0) {
            return false;
        }

        return true;
    }
}
