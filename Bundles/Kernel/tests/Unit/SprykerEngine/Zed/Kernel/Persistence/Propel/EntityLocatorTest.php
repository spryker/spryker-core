<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Persistence\Propel;

use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\Propel\EntityLocator;

/**
 * @group Kernel
 * @group Persistence
 * @group Locator
 * @group EntityLocator
 */
class EntityLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testLocateEntityShouldReturnEntityOfGivenBundle()
    {
        $entityLocator = new EntityLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Persistence\\Propel\\Fixtures\\'
        );
        $entity = $entityLocator->locate('Kernel', Locator::getInstance(), 'FooEntity');

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Persistence\Propel\Fixtures\FooEntity', $entity);
    }
}
