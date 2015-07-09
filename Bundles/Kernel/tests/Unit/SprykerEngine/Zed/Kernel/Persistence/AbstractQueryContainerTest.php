<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainerLocator;
use SprykerEngine\Zed\Kernel\Locator;

/**
 * @group Kernel
 * @group Persistence
 * @group AbstractQueryContainer
 */
class AbstractQueryContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateInstanceShouldInjectDependencyContainerIfOneExists()
    {
        $locator = new QueryContainerLocator(
            '\\Unit\\SprykerEngine\\Zed\\{{bundle}}{{store}}\\Persistence\\Fixtures\\Factory'
        );
        $locatedClass = $locator->locate('Kernel', Locator::getInstance());

        $this->assertInstanceOf('Unit\SprykerEngine\Zed\Kernel\Persistence\Fixtures\KernelQueryContainer', $locatedClass);
        $this->assertInstanceOf(
            'Unit\SprykerEngine\Zed\Kernel\Persistence\Fixtures\KernelDependencyContainer',
            $locatedClass->getDepCon()
        );
    }

}
