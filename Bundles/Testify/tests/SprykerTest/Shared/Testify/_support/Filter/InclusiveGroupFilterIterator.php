<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Filter;

use PHPUnit\Framework\TestSuite;
use RecursiveFilterIterator;
use RecursiveIterator;

/**
 * Filters tests that have all the requested groups
 */
class InclusiveGroupFilterIterator extends RecursiveFilterIterator
{
    /**
     * @var string[]
     */
    protected $testGroups = [];

    /**
     * @param \RecursiveIterator $iterator
     * @param string[] $groups
     * @param \PHPUnit\Framework\TestSuite $suite
     */
    public function __construct(RecursiveIterator $iterator, array $groups, TestSuite $suite)
    {
        parent::__construct($iterator);

        $this->setTestGroups(
            $this->getSuiteGroupsIntersection($suite, $groups)
        );
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        $test = $this->getInnerIterator()->current();

        if ($test instanceof TestSuite) {
            return true;
        }

        return in_array(
            spl_object_hash($test),
            $this->testGroups,
            true
        );
    }

    /**
     * @param \PHPUnit\Framework\TestSuite $suite
     * @param string[] $inclusiveGroups
     *
     * @return array
     */
    protected function getSuiteGroupsIntersection(TestSuite $suite, array $inclusiveGroups): array
    {
        $suiteGroups = array_intersect_key(
            $suite->getGroupDetails(),
            array_flip($inclusiveGroups)
        );

        if (array_diff_key(array_flip($inclusiveGroups), $suiteGroups) === []) {
            return $suiteGroups;
        }

        return [];
    }

    /**
     * @param string[] $suiteGroups
     *
     * @return void
     */
    protected function setTestGroups(array $suiteGroups): void
    {
        foreach ($suiteGroups as $tests) {
            $testHashes = array_map(
                'spl_object_hash',
                $tests
            );

            $this->testGroups = count($this->testGroups) === 0
                ? $testHashes
                : array_intersect($testHashes, $this->testGroups);
        }
    }
}
