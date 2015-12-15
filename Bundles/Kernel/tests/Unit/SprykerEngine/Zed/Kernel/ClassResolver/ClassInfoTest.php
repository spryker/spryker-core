<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver;

use Spryker\Zed\Kernel\ClassResolver\ClassInfo;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ClassInfo
 */
class ClassInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSetClassNameMustReturnSelf()
    {
        $classInfo = new ClassInfo();
        $this->assertInstanceOf(
            'Spryker\Zed\Kernel\ClassResolver\ClassInfo',
            $classInfo->setClass($classInfo)
        );
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractNamespaceFromClass()
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Spryker', $classInfo->getNamespace());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractApplicationFromClass()
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Zed', $classInfo->getApplication());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractBundleFromClass()
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('Kernel', $classInfo->getBundle());
    }

    /**
     * @return void
     */
    public function testSetClassNameMustExtractLayerFromClass()
    {
        $classInfo = new ClassInfo();
        $classInfo->setClass($classInfo);

        $this->assertSame('ClassResolver', $classInfo->getLayer());
    }

}
