<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Unit\Spryker\Zed\Kernel\ClassResolver\AbstractResolverTest;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group ConfigResolver
 */
class ConfigResolverTest extends AbstractResolverTest
{

    protected $coreClass = 'Unit\\Spryker\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';
    protected $projectClass = 'Unit\\Pyz\\Zed\\Kernel\\ClassResolver\\Fixtures\\KernelConfig';
    protected $storeClass = 'Unit\\Pyz\\Zed\\KernelDE\\ClassResolver\\Fixtures\\KernelConfig';
    protected $classPattern = 'Unit\\%namespace%\\Zed\\%bundle%%store%\\ClassResolver\\Fixtures\\%bundle%Config';
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMock(BundleConfigResolver::class, $methods);

        return $resolverMock;
    }

}
