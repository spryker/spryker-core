<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Persistence;

use Unit\SprykerEngine\Zed\Kernel\Persistence\Fixtures\Factory;

/**
 * @group Kernel
 * @group Persistence
 * @group Locator
 * @group AbstractFactory
 */
class AbstractFactoryTest extends \PHPUnit_Framework_TestCase
{

    const BUNDLE_NAME = 'Kernel';

    public function testExistsShouldReturnFalseIfClassCanNotCreated()
    {
        $factory = new Factory(self::BUNDLE_NAME);

        $this->assertFalse($factory->exists('not existing'));
    }

}
