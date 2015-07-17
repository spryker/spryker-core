<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication;

use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooModel;
use Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\Factory;

/**
 * @group Kernel
 * @group Communication
 * @group AbstractFactory
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';

    public function testCreateWithGivenClassNameShouldReturnClassInstance()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $model = $factory->create('FooModel');

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\FooModel', $model);
    }

    public function testCreateWithGivenClassNameAndAnArgumentShouldReturnClassInstance()
    {
        $factory = new Factory(self::BUNDLE_NAME);
        $fooModel = new FooModel();
        $model = $factory->create('BarModel', $fooModel);

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\BarModel', $model);
    }

}
