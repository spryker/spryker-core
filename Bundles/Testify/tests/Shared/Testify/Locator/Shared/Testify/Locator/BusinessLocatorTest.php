<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Shared\Testify\Locator;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Testify\Exception\InvalidNamespacesException;
use Spryker\Shared\Testify\Locator\BusinessLocator;

/**
 * @group Spryker
 * @group Shared
 * @group Testify
 * @group Locator
 */
class BusinessLocatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCanBeInstantiatedWithNamespacesArray()
    {
        $namespaces = ['Spryker'];
        $businessLocator = new BusinessLocator($namespaces);

        $this->assertInstanceOf(LocatorLocatorInterface::class, $businessLocator);
    }

    /**
     * @return void
     */
    public function testCreateInstanceThrowsExceptionWhenNamespacesNotValid()
    {
        $this->expectException(InvalidNamespacesException::class);

        $namespaces = [];
        new BusinessLocator($namespaces);
    }

}
