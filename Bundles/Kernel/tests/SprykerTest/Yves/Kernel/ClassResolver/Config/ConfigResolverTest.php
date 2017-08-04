<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\ClassResolver\Config;

use Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigResolver;
use SprykerTest\Yves\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group ClassResolver
 * @group Config
 * @group ConfigResolverTest
 * Add your own group annotations below this line
 */
class ConfigResolverTest extends AbstractResolverTest
{

    /**
     * @var string
     */
    protected $coreClass = 'Unit\\Spryker\\Yves\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\ProjectNamespace\\Yves\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\ProjectNamespace\\Yves\\KernelDE\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Yves\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Config';

    /**
     * @var string
     */
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(BundleConfigResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }

}
