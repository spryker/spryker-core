<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Definition\Merger;

use Codeception\Test\Unit;
use Spryker\Zed\Search\Business\Definition\Merger\IndexDefinitionMerger;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Definition
 * @group Merger
 * @group IndexDefinitionMergerTest
 * Add your own group annotations below this line
 */
class IndexDefinitionMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testMergeMergesDefinitions(): void
    {
        $definitionA = ['foo' => ['bar' => 1, 'baz' => 2]];
        $definitionB = ['foo' => ['bar' => 2]];
        $expected = ['foo' => ['bar' => 2, 'baz' => 2]];

        $indexDefinitionMerger = new IndexDefinitionMerger();

        $merged = $indexDefinitionMerger->merge($definitionA, $definitionB);
        $this->assertSame($expected, $merged);
    }
}
