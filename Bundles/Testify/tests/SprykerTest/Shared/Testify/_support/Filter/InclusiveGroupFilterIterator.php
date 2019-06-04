<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Filter;

use PHPUnit\Framework\TestSuite;
use RecursiveFilterIterator;
use RecursiveIterator;

class InclusiveGroupFilterIterator extends RecursiveFilterIterator
{
    /**
     * @var string[]
     */
    protected $groupTests = [];

    /**
     * @param \RecursiveIterator $iterator
     * @param array $groups
     * @param \PHPUnit\Framework\TestSuite $suite
     */
    public function __construct(RecursiveIterator $iterator, array $groups, TestSuite $suite)
    {
        parent::__construct($iterator);

        $this->setGroupTests(
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
            $this->groupTests,
            true
        );
    }

    /**
     * @param \PHPUnit\Framework\TestSuite $suite
     * @param array $inclusiveGroups
     *
     * @return array
     */
    protected function getSuiteGroupsIntersection(TestSuite $suite, array $inclusiveGroups): array
    {
        $suiteGroups = array_intersect_key(
            $suite->getGroupDetails(),
            array_flip($inclusiveGroups)
        );

        if (count($suiteGroups) !== count($inclusiveGroups)) {
            return [];
        }

        return $suiteGroups;
    }

    /**
     * @param array $suiteGroups
     *
     * @return void
     */
    protected function setGroupTests(array $suiteGroups): void
    {
        foreach ($suiteGroups as $tests) {
            $testHashes = array_map(
                'spl_object_hash',
                $tests
            );

            $this->groupTests = count($this->groupTests) === 0
                ? $testHashes
                : array_intersect($testHashes, $this->groupTests);
        }
    }
}
