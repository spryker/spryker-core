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
    protected $coreClass = 'Spryker\\Service\\Kernel\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $projectClass = 'ProjectNamespace\\Service\\Kernel\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Service\\KernelDE\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Service\\%bundle%%store%\\ClassResolver\\%bundle%Config';

    /**
     * @var string
     */
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(BundleConfigResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
