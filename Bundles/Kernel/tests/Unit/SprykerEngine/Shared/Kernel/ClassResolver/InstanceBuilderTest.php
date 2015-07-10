<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures\Foo;
use SprykerEngine\Shared\Kernel\ClassResolver\InstanceBuilder;

/**
 * @group Kernel
 * @group ClassResolver
 * @group InstanceBuilder
 */
class InstanceBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateWithoutArgumentsShouldReturnInstance()
    {
        $instanceBuilder = new InstanceBuilder();
        $instance = $instanceBuilder->createInstance('Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures\Foo');

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures\Foo', $instance);
    }

    public function testCreateWithArgumentsShouldReturnInstance()
    {
        $instanceBuilder = new InstanceBuilder();
        $data = 'Foo';
        $instance = $instanceBuilder->createInstance(
            'Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures\Foo',
            [$data]
        );

        $this->assertInstanceOf('Unit\SprykerEngine\Shared\Kernel\ClassResolver\Fixtures\Foo', $instance);
        $this->assertSame($data, $instance->getData());
    }

}
