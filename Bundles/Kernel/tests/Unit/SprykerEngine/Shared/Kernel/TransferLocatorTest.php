<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\TransferLocator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group Kernel
 * @group Locator
 * @group TransferLocator
 */
class TransferLocatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function transferClassNameProvider()
    {
        return [
            ['Foo', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\Transfer\Foo'],
            ['FooBar', 'Unit\SprykerEngine\Shared\Kernel\Fixtures\Transfer\Foo\Bar'],
        ];
    }

    /**
     * @dataProvider transferClassNameProvider
     *
     * @param $className
     * @param $fullyQualifiedClassName
     */
    public function testLocateShouldReturnClassInstanceIfItCanBeLocated($className, $fullyQualifiedClassName)
    {
        $transferLocator = new TransferLocator(
            '\\Unit\\{{namespace}}\\Shared\\{{bundle}}{{store}}\\Fixtures\\Transfer\\'
        );
        $locator = Locator::getInstance();
        $locatedTransfer = $transferLocator->locate('Kernel', $locator, $className);

        $this->assertInstanceOf($fullyQualifiedClassName, $locatedTransfer);
    }

}
