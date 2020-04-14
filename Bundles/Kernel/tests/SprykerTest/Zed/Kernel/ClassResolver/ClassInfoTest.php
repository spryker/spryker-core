<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\ClassResolver;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group ClassInfoTest
 * Add your own group annotations below this line
 */
class ClassInfoTest extends Unit
{
    /**
     * @return void
     */
    public function testSetClassNameMustReturnSelf(): void
    {
        $classInfo = new ClassInfo();
        $this->assertInstanceOf(
            ClassInfo::class,
            $classInfo->setClass($classInfo)
        );
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractNamespaceFromClass(): void
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Spryker', $classInfo->getNamespace());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractApplicationFromClass(): void
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Shared', $classInfo->getApplication());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractBundleFromClass(): void
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Kernel', $classInfo->getModule());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractLayerFromClass(): void
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('ClassResolver', $classInfo->getLayer());
    }
}
