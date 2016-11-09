<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\Config;

use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ClassResolver
 * @group Config
 * @group ConfigResolverTest
 */
class ConfigResolverTest extends AbstractResolverTest
{

    /**
     * @var string
     */
    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $projectClass = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $storeClass = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelConfig';

    /**
     * @var string
     */
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Config';

    /**
     * @var string
     */
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(BundleConfigResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }

}
