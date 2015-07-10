<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business;

use Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\Factory;
use Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\FooModel;

/**
 * @group Kernel
 * @group Business
 * @group AbstractFactory
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';

    public function testCreateWithGivenClassNameShouldReturnClassInstance()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $model = $factory->create('FooModel');

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\FooModel', $model);
    }

    public function testCreateWithGivenClassNameAndAnArgumentShouldReturnClassInstance()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $fooModel = new FooModel();
        $model = $factory->create('BarModel', $fooModel);

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Business\Fixtures\BarModel', $model);
    }

    public function testExistsShouldReturnFalseIfClassCanNotCreated()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertFalse($factory->exists('not existing'));
    }

    public function testExistsShouldReturnTrueIfClassCanBeCreated()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertTrue($factory->exists('FooModel'));
    }

}
