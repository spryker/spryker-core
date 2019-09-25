<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\ClassResolver;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group ClassResolver
 * @group AbstractClassResolverTest
 * Add your own group annotations below this line
 */
class AbstractClassResolverTest extends Unit
{
    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $reflectionResolver = new ReflectionClass(AbstractClassResolver::class);
        $reflectionProperty = $reflectionResolver->getProperty('cache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue([]);
    }

    /**
     * @return void
     */
    public function testCanResolveWithExistingClass()
    {
        $classExists = true;
        $abstractClassResolverMock = $this->getAbstractClassResolverMock($classExists);
        $callerClass = 'Namespace\\Application\\Bundle\\Layer\\CallerClass';

        $this->assertTrue($abstractClassResolverMock->setCallerClass($callerClass)->canResolve());
    }

    /**
     * @return void
     */
    public function testCanResolveNotExistingClass()
    {
        $classExists = false;
        $abstractClassResolverMock = $this->getAbstractClassResolverMock($classExists);
        $callerClass = 'Namespace\\Application\\Bundle\\Layer\\CallerClass';

        $this->assertFalse($abstractClassResolverMock->setCallerClass($callerClass)->canResolve());
    }

    /**
     * @param bool $classExists
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver
     */
    private function getAbstractClassResolverMock($classExists)
    {
        $abstractClassResolverMock = $this->getMockForAbstractClass(AbstractClassResolver::class, [], '', true, true, true, ['classExists']);
        $abstractClassResolverMock->method('classExists')->willReturn($classExists);

        return $abstractClassResolverMock;
    }
}
