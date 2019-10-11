<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Search\Index;

use Codeception\Test\Unit;
use Spryker\Shared\Search\Exception\IndexNameException;
use Spryker\Shared\Search\Index\IndexNameResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Search
 * @group Index
 * @group IndexNameResolverTest
 * Add your own group annotations below this line
 */
class IndexNameResolverTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Search\SearchSharedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testResolveWillReturnMappedNameWhenIndexNameInMap(): void
    {
        $indexNameMap = ['foo' => 'bar'];
        $indexNameResolver = new IndexNameResolver($indexNameMap);

        $this->assertSame('bar', $indexNameResolver->resolve('foo'));
    }

    /**
     * @return void
     */
    public function testResolveThrowsExceptionWhenIndexNameNotInMap(): void
    {
        $indexNameMap = [];
        $indexNameResolver = new IndexNameResolver($indexNameMap);

        $this->expectException(IndexNameException::class);
        $indexNameResolver->resolve('foo');
    }
}
