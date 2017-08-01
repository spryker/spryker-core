<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel\ClassResolver\Config;

use Spryker\Service\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver;
use SprykerTest\Service\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
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
    protected $coreClass = 'Unit\\Spryker\\Service\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\ProjectNamespace\\Service\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\ProjectNamespace\\Service\\KernelDE\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Service\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Config';

    /**
     * @var string
     */
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(BundleConfigResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }

}
